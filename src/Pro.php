<?php
/**
 * Pro Main App
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Pro;
use iMoneza\Exception;
use iMoneza\Library\WordPress\Base;
use iMoneza\WordPress\Pro\Controller\Options\RemoteRefresh;
use iMoneza\WordPress\Pro\Filter\ExternalResourceKey;


/**
 * Class Pro
 * @package iMoneza\WordPress
 */
class Pro extends Base
{
    /**
     * @var string the url for the client side js
     */
    const CLIENT_SIDE_JAVASCRIPT_URL = 'https://accessui.imoneza.com/assets/imoneza.js';

    /**
     * Pro constructor.
     * @param string $pluginDir
     */
    public function __construct($pluginDir)
    {
        parent::__construct($pluginDir);

        $di = $this->di;

        // DI Services
        $di['service.imoneza'] = function () {
            return new \iMoneza\WordPress\Pro\Service\iMoneza();
        };

        // DI Controllers
        $di['controller.options.pro-first-time'] = function($di) {
            return new \iMoneza\WordPress\Pro\Controller\Options\ProFirstTime($di['view'], $di['service.imoneza']);
        };
        $di['controller.options.access'] = function($di) {
            return new \iMoneza\WordPress\Pro\Controller\Options\Access($di['view'], $di['service.imoneza']);
        };
        $di['controller.options.remote-refresh'] = function($di) {
            return new \iMoneza\WordPress\Pro\Controller\Options\RemoteRefresh($di['view'], $di['service.imoneza']);
        };
    }

    /**
     * Invoke the APP
     */
    public function __invoke()
    {
        parent::__invoke();

        $this->registerDeactivationHook();
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
        parent::enqueueAdminScripts();

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        });
    }

    /**
     * Actions to be taken when this is uninstalled
     */
    protected function registerDeactivationHook()
    {
        register_deactivation_hook('imoneza-pro/imoneza-pro.php', function() {
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
            /** @var \iMoneza\WordPress\Pro\Controller\Options\RemoteRefresh $controller */
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

                    /** @var \iMoneza\WordPress\Pro\Service\iMoneza $service */
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
     * Show a message if both versions are enabled
     */
    protected function addAdminNoticeToDisableLite()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if (is_plugin_active('imoneza/imoneza.php')) {
            add_action('admin_notices', function() {
                // this is inline because the view renderer has a problem when both versions are installed
                echo '<div class="notice notice-error"><p>';
                echo __('iMoneza PRO and Standard versions should not be used at the same time.  Please disable the Standard version before continuing.', 'imoneza');
                echo '</p></div>';
            });
        }
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
                /** @var \iMoneza\WordPress\Pro\Service\iMoneza $service */
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
        parent::registerAdminAjax();

        $di = $this->di;

        add_action('wp_ajax_options_pro_first_time', function () use ($di) {
            /** @var \iMoneza\WordPress\Pro\Controller\Options\PROFirstTime $controller */
            $controller = $di['controller.options.pro-first-time'];
            $controller();
        });
        add_action('wp_ajax_options_access', function () use ($di) {
            /** @var \iMoneza\WordPress\Pro\Controller\Options\Access $controller */
            $controller = $di['controller.options.access'];
            $controller();
        });
        add_action('wp_ajax_options_remote_refresh', function () use ($di) {
            /** @var \iMoneza\WordPress\Pro\Controller\Options\RemoteRefresh $controller */
            $controller = $di['controller.options.remote-refresh'];
            $controller();
        });
    }

    /**
     * add premium colors and all the rest of the css
     */
    protected function addSupportingUserCSS()
    {
        parent::addSupportingUserCSS();

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

    /**
     * Add a shortcode for premium adblock notifications
     */
    protected function addAdblockNotificationShortCode()
    {
        add_shortcode('imoneza_adblock_notification', function() {
            return '<div id="imoneza-adblock-notification"></div>';
        });
    }
}