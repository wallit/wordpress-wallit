<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress;
use iMoneza\Exception;
use iMoneza\WordPress\Controller\Options\RemoteRefresh;
use iMoneza\WordPress\Filter\ExternalResourceKey;
use Pimple\Container;

/**
 * Class Pro
 * @package iMoneza\WordPress
 */
class App
{
    use Traits\Options;

    /**
     * @var string the url for the client side js
     */
    const CLIENT_SIDE_JAVASCRIPT_URL = 'https://cdn.imoneza.com/paywall.min.js';
    
    /**
     * @var Container
     */
    protected $di;

    /**
     * @var string the base URL for this plugin
     */
    protected $pluginBaseUrl;
    
    /**
     * constructor.
     * @param string $pluginDir
     */
    public function __construct($pluginDir)
    {
        $pluginBaseUrl = $this->pluginBaseUrl = sprintf('%s/%s', WP_PLUGIN_URL, plugin_basename($pluginDir));

        $this->di = $di = new Container();

        // DI Controllers
        $di['controller.options.display'] = function($di) {
            return new \iMoneza\WordPress\Controller\Options\Display($di['view']);
        };

        // View
        $di['view'] = function($di) use ($pluginDir, $pluginBaseUrl) {
            $factory = new \Aura\View\ViewFactory();
            $view = $factory->newInstance();

            $registry = $view->getViewRegistry();
            $registry->setPaths([$pluginDir . '/src/View']);

            $helpers = $view->getHelpers();
            $helpers->set('assetUrl', function($assetUrl) use ($pluginDir, $pluginBaseUrl) {
                return sprintf('%s/assets/%s', $pluginBaseUrl, $assetUrl);
            });

            return $view;
        };

        $di = $this->di;

        // DI Services
        $di['service.imoneza'] = function () {
            return new \iMoneza\WordPress\Service\iMoneza();
        };

        // DI Controllers
        $di['controller.options.pro-first-time'] = function($di) {
            return new \iMoneza\WordPress\Controller\Options\ProFirstTime($di['view'], $di['service.imoneza']);
        };
        $di['controller.options.access'] = function($di) {
            return new \iMoneza\WordPress\Controller\Options\Access($di['view'], $di['service.imoneza']);
        };
        $di['controller.options.remote-refresh'] = function($di) {
            return new \iMoneza\WordPress\Controller\Options\RemoteRefresh($di['view'], $di['service.imoneza']);
        };
    }

    /**
     * Invoke the APP
     */
    public function __invoke()
    {
        if (is_admin()) {
            $this->initAdminItems();
            $this->registerAdminAjax();
            $this->enqueueAdminScripts();
        }
        else {
            $this->addPremiumIndicator();
            $this->addSupportingUserCSS();
        }

        $this->registerActivationDeactivationHooks();
        $this->addCron();

        if (is_admin()) {
            $this->addAdminNoticeToDisableLite();
            $this->addAdminNoticeConfigNeeded();
            $this->addPostMetaBox();
            $this->addPublishPostAction();
        }
        else {
            $this->addClientSideAccessControl();
            $this->addServerSideAccessControl();
            $this->addAdblockNotificationShortCode();
        }
    }

    /**
     * Add the admin scripts
     */
    protected function enqueueAdminScripts()
    {
        $pluginBaseUrl = $this->pluginBaseUrl;

        add_action('admin_enqueue_scripts', function () use ($pluginBaseUrl) {
            wp_register_style('imoneza-admin-css', $pluginBaseUrl . '/assets/css/admin.css');
            wp_enqueue_style('imoneza-admin-css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('imoneza-admin-js', $pluginBaseUrl . '/assets/js/admin.js', [], false, true);
        });
        
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        });
    }

    /**
     * Adds the premium indicator filter if need be
     */
    protected function addPremiumIndicator()
    {
        $options = $this->getOptions();
        if ($options->isIndicatePremiumContent()) {
            add_filter('the_title', function($title) use ($options) {
                if (has_tag('premium') && in_the_loop()) {
                    $replacement = '<span class="imoneza-premium-indicator ' . $options->getPremiumIndicatorIconClass() . '">';
                    if ($options->getPremiumIndicatorIconClass() == 'imoneza-custom-indicator') $replacement .= $options->getPremiumIndicatorCustomText();
                    $replacement .= '</span> ' . $title;
                    $title = $replacement;
                }

                return $title;
            });
        }
    }


    /**
     * Actions to be taken when this is uninstalled
     */
    protected function registerActivationDeactivationHooks()
    {
       
        register_deactivation_hook('imoneza/imoneza.php', function() {
            wp_clear_scheduled_hook('imoneza_hourly');
        });
    }

    /**
     * this is scheduled hourly
     *
     * It gets the options to keep them fresh, and then also checks for unpriced managed posts
     */
    protected function addCron()
    {
        $di = $this->di;
        $options = $this->getOptions();

        add_action('imoneza_hourly', function() use ($di, $options) {
            /** @var \iMoneza\WordPress\Controller\Options\RemoteRefresh $controller */
            $controller = $di['controller.options.remote-refresh'];
            $controller(RemoteRefresh::DO_NOT_SHOW_VIEW);

            if ($options->isDynamicallyCreateResources()) {
                // get all items that don't have a meta of _pricing-group-id (20 at a time - only use the first page)
                $query = new \WP_Query([
                    'post_type' =>  'post',
                    'posts_per_page'    =>  20,
                    'meta_query'    =>  [
                        ['key'=>'_pricing-group-id', 'value'=>'', 'compare'=>'NOT EXISTS']
                    ]
                ]);

                if ($query->have_posts()) {
                    $service = $di['service.imoneza'];
                    $service->setManagementApiKey($options->getManagementApiKey())->setManagementApiSecret($options->getManagementApiSecret());

                    $defaultPricingGroupId = $options->getDefaultPricingGroup()->getPricingGroupID();

                    // loop through this first page
                    while ($query->have_posts()) {
                        $query->the_post();
                        $post = get_post();

                        $service->createOrUpdateResource($post, $defaultPricingGroupId);
                        add_post_meta($post->ID, '_override-pricing', false, true);
                        add_post_meta($post->ID, '_pricing-group-id', $defaultPricingGroupId, true);
                    }
                }
            }
        });

        /** schedule cron if it hasn't been scheduled - making sure we have it configured too */
        if ($this->getOptions()->isProInitialized()) {
            if (wp_next_scheduled('imoneza_hourly') === false) wp_schedule_event(time(), 'hourly', 'imoneza_hourly');
        }
    }

    /**
     * Used to add the javascript to the page if necessary
     */
    protected function addClientSideAccessControl()
    {
        $options = $this->getOptions();
        $di = $this->di;

        add_action('wp_head', function() use ($di, $options) {
            if ($options->isAccessControlClient() && $options->getAccessApiKey()) {
                $post = is_single() ? get_post() : null;
                if ($post) {
                    $filter = new ExternalResourceKey();
                    $js = self::CLIENT_SIDE_JAVASCRIPT_URL;
                    if ($overrideJs = getenv('CLIENT_SIDE_JAVASCRIPT_URL')) $js = $overrideJs;
                    $view = $di['view'];
                    $view->setView('client-side-access-header-js');
                    $view->setData(['apiKey'=>$options->getAccessApiKey(), 'resourceKey'=>$filter->filter($post), 'javascriptUrl'=>$js]);
                    echo $view();
                }
            }
        });
    }

    /**
     * If there is server side access, check the items here
     */
    protected function addServerSideAccessControl()
    {
        $options = $this->getOptions();
        $di = $this->di;

        add_action('wp', function() use ($options, $di) {
            if ($options->isAccessControlServer() && $options->getAccessApiKey()) {
                $post = is_single() ? get_post() : null;
                if ($post) {
                    $iMonezaTUT = isset($_GET['iMonezaTUT']) ? $_GET['iMonezaTUT'] : null;

                    /** @var \iMoneza\WordPress\Service\iMoneza $service */
                    $service = $di['service.imoneza'];
                    $service->setAccessApiKey($options->getAccessApiKey())->setAccessApiSecret($options->getAccessApiSecret());

                    try {
                        if ($redirectURL = $service->getResourceAccessRedirectURL($post, $iMonezaTUT)) {
                            $currentURL = sprintf('%s://%s%s', $_SERVER['SERVER_PROTOCOL'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
                            wp_redirect($redirectURL . '&originalURL=' . $currentURL);
                        }
                    }
                    catch (Exception\iMoneza $e) {
                        // do nothing - as we don't want to error out on content
                    }
                }
            }
        });
    }

    /**
     * Show the config message to admin if need be
     */
    protected function addAdminNoticeConfigNeeded()
    {
        global $pagenow;
        $options = $this->getOptions();
        $di = $this->di;
        if (!$options->isProInitialized()) {
            if (!($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'imoneza')) {
                add_action('admin_notices', function() use ($di) {
                    $view = $di['view'];
                    $view->setView('admin/notify-config-needed-pro');
                    echo $view();
                });
            }
        }
    }

    /**
     * Add admin items like menu and and settings
     */
    protected function initAdminItems()
    {
        $di = $this->di;

        add_action('admin_init', function () {
            register_setting(self::$optionsKey, self::$optionsKey);
        });

        $settingsControllerString = $this->getOptions()->isProInitialized() ? 'controller.options.access' : 'controller.options.pro-first-time';
        add_action('admin_menu', function () use ($settingsControllerString, $di) {
            add_menu_page('iMoneza Settings', 'iMoneza', 'manage_options', 'imoneza', $di[$settingsControllerString],
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjAiIHk9IjAiIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwLCAwLCAxNTAsIDE1MCI+CiAgPGcgaWQ9IkxheWVyXzEiPgogICAgPGc+CiAgICAgIDxwYXRoIGQ9Ik0yNS44MzEsMTExLjc4NiBMNTQuOTcyLDY0LjcyIEw0OS41NjQsNjQuNzIgTDQ5LjU2NCw1MC41IEw3OC4zMDUsNTAuNSBMNzguMzA1LDEwMS4xNzEgTDEwMS41MzcsNjQuNzIgTDk3LjAzMSw2NC43MiBMOTcuNTMxLDUwLjUgTDEyNS4xNyw1MC41IEwxMjUuMTcsMTExLjc4NiBMMTMyLjE4LDExMS43ODYgTDEzMi4xOCwxMjUuNjA1IEwxMDYuNTQ0LDEyNS42MDUgTDEwNi41NDQsMTExLjc4NiBMMTExLjE1MSwxMTEuNzg2IEwxMTEuMTUxLDc1LjIzNSBMNzkuMjA2LDEyNS42MDUgTDY0LjQ4NSwxMjUuNjA1IEw2NC40ODUsNzUuMjM1IEw0Mi4xNTQsMTExLjc4NiBMNDguMzYzLDExMS43ODYgTDQ4LjM2MywxMjUuNjA1IEwxNy44MiwxMjUuNjA1IEwxNy44MiwxMTEuNzg2IHoiIGZpbGw9IiM0NTQ2NDMiLz4KICAgICAgPHBhdGggZD0iTTc1LjA1MywzNS40MDcgQzc1LjA1Myw0MS40ODcgNzAuMTI2LDQ2LjQxOSA2NC4wNDEsNDYuNDE5IEM1Ny45NjEsNDYuNDE5IDUzLjAzMiw0MS40ODcgNTMuMDMyLDM1LjQwNyBDNTMuMDMyLDI5LjMyNyA1Ny45NjEsMjQuMzk1IDY0LjA0MSwyNC4zOTUgQzcwLjEyNiwyNC4zOTUgNzUuMDUzLDI5LjMyNyA3NS4wNTMsMzUuNDA3IiBmaWxsPSIjNDU0NjQzIi8+CiAgICA8L2c+CiAgPC9nPgo8L3N2Zz4K'
                , 100);
            add_submenu_page('imoneza', 'iMoneza Settings', 'Access Settings', 'manage_options', 'imoneza');
            add_submenu_page('imoneza', 'iMoneza Settings', 'Display Settings', 'manage_options', 'imoneza-display', $di['controller.options.display']);
        });
    }

    /**
     * Add the imoneza box to the WordPress post page
     */
    protected function addPostMetaBox()
    {
        $options = $this->getOptions();
        $di = $this->di;
        $pluginBaseUrl = $this->pluginBaseUrl;

        add_action('add_meta_boxes', function() use ($di, $options, $pluginBaseUrl) {
            $title = sprintf('<img src="%s" style="height: 16px; vertical-align: middle">', $pluginBaseUrl . '/assets/images/logo-rectangle-small.png');

            add_meta_box('imoneza-post-pricing', $title, function(\WP_Post $post) use ($di, $options) {
                $editing = !empty($post->ID);

                $pricingGroupSelected = '';
                if ($editing) {
                    $pricingGroupSelected = get_post_meta($post->ID, '_pricing-group-id', true);
                }
                if (empty($selected)) {
                    $pricingGroupSelected = $options->getDefaultPricingGroup();
                }
                $overrideChecked = get_post_meta($post->ID, '_override-pricing', true);

                $view = $di['view'];
                $view->setView('admin/post-pricing');
                $view->setData([
                    'overrideChecked'   =>  $overrideChecked,
                    'dynamicallyCreateResources'=>$options->isDynamicallyCreateResources(),
                    'pricingGroupSelected'=>$pricingGroupSelected,
                    'pricingGroups'=>$options->getPricingGroups()
                ]);
                echo $view();
            }, 'post');
        });
    }

    /**
     * Handles updating or adding access control information to posts
     */
    protected function addPublishPostAction()
    {
        $di = $this->di;
        $options = $this->getOptions();

        add_action('publish_post', function($postId) use ($di, $options) {
            /** @var \WP_Post $post */
            $post = get_post($postId);

            $overridePricing = !empty($_POST['override-pricing']);
            if ($options->isDynamicallyCreateResources() || $overridePricing) {
                $pricingGroupId = $_POST['pricing-group-id'];
                /** @var \iMoneza\WordPress\Service\iMoneza $service */
                $service = $di['service.imoneza'];
                $service->setManagementApiKey($options->getManagementApiKey())->setManagementApiSecret($options->getManagementApiSecret());

                try {
                    $service->createOrUpdateResource($post, $pricingGroupId);
                }
                catch (Exception\iMoneza $e) {
                    trigger_error($e->getMessage(), E_USER_ERROR);
                }

                $new = substr($_POST['_wp_http_referer'], -12) == 'post-new.php';

                if ($new) {
                    add_post_meta($postId, '_override-pricing', $overridePricing, true);
                    add_post_meta($postId, '_pricing-group-id', $pricingGroupId, true);
                }
                else {
                    update_post_meta($postId, '_override-pricing', $overridePricing);
                    update_post_meta($postId, '_pricing-group-id', $pricingGroupId);
                }
            }
        });
    }

    /**
     * Registers the admin ajax functionality
     */
    protected function registerAdminAjax()
    {
        $di = $this->di;

        add_action('wp_ajax_options_display', function () use ($di) {
            /** @var \iMoneza\Library\WordPress\Controller\Options\Display $controller */
            $controller = $di['controller.options.display'];
            $controller();
        });
        
        add_action('wp_ajax_options_pro_first_time', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\FirstTime $controller */
            $controller = $di['controller.options.pro-first-time'];
            $controller();
        });
        add_action('wp_ajax_options_access', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\Access $controller */
            $controller = $di['controller.options.access'];
            $controller();
        });
        add_action('wp_ajax_options_remote_refresh', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\RemoteRefresh $controller */
            $controller = $di['controller.options.remote-refresh'];
            $controller();
        });
    }

    /**
     * add premium colors and all the rest of the css
     */
    protected function addSupportingUserCSS()
    {
        $pluginBaseUrl = $this->pluginBaseUrl;

        if ($this->getOptions()->isNotifyAdblocker() || $this->getOptions()->isIndicatePremiumContent()) {
            add_action('wp_enqueue_scripts', function() use ($pluginBaseUrl) {
                $dependencies = $this->getOptions()->isIndicatePremiumContent() ? ['dashicons'] : [];
                wp_register_style('imoneza-user-css', $pluginBaseUrl . '/assets/css/user.css', $dependencies);
                wp_enqueue_style('imoneza-user-css');
            });
        }

        $options = $this->getOptions();
        $di = $this->di;

        if ($options->isIndicatePremiumContent()) {
            add_action('wp_head', function() use ($di, $options) {
                $view = $di['view'];
                $view->setView('custom-premium-indicator-color-css');
                $view->setData(['color'=>$options->getPremiumIndicatorCustomColor()]);
                echo $view();
            });
        }
    }

}