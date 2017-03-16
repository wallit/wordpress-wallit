<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress;
use iMoneza\Exception;
use iMoneza\WordPress\Controller;
use iMoneza\WordPress\Model\Options;
use iMoneza\WordPress\Service;
use iMoneza\WordPress\Filter;
use Pimple\Container;

/**
 * Class Pro
 * @package iMoneza\WordPress
 */
class App
{
    use Traits\Options;

    /**
     * @var Container DI Container 
     */
    protected $di;
    
    /**
     * Set up DI and Base URL in the contructor
     * 
     * @param string $pluginDir
     */
    public function __construct($pluginDir)
    {
        $pluginBaseUrl = sprintf('%s/%s', WP_PLUGIN_URL, plugin_basename($pluginDir));
        $this->di = $di = new Container();

        // DI Filters
        $di['filter.asset-url'] = function () use ($pluginBaseUrl) {
            return new Filter\AssetUrl($pluginBaseUrl);
        };
        $di['filter.external-resource-key'] = function () {
            return new Filter\ExternalResourceKey();
        };
        
        // DI Services
        $di['service.imoneza'] = function () use ($di) {
            return new Service\iMoneza($di['filter.external-resource-key']);
        };
        $di['service.post'] = function () use ($di) {
            return new Service\Post();
        };
        
        // DI Controllers
        $di['controller.options.internal'] = function ($di) {
            return new Controller\Options\Internal($di['view']);
        };
        $di['controller.options.first-time'] = function ($di) {
            return new Controller\Options\FirstTime($di['view'], $di['service.imoneza']);
        };
        $di['controller.options.access'] = function ($di) {
            return new Controller\Options\Access($di['view'], $di['service.imoneza'], $di['service.post']);
        };
        $di['controller.options.remote-refresh'] = function ($di) {
            return new Controller\Options\RemoteRefresh($di['view'], $di['service.imoneza']);
        };

        // View
        $di['view'] = function ($di) use ($pluginDir) {
            $factory = new \Aura\View\ViewFactory();
            $view = $factory->newInstance();

            $registry = $view->getViewRegistry();
            $registry->setPaths([$pluginDir . '/src/View']);

            $helpers = $view->getHelpers();
            $helpers->set('assetUrl', $di['filter.asset-url']);

            return $view;
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
            $this->addAdminNoticeConfigNeeded();
            $this->addPostMetaBox();
            $this->addPublishPostAction();
        }
        else {
            $this->addClientSideAccessControl();
            $this->addServerSideAccessControl();
        }

        $this->registerDeactivationHooks();
        $this->addCron();
    }

    /**
     * Add admin items like menu and and settings
     */
    protected function initAdminItems()
    {
        $di = $this->di;
        $options = $this->getOptions();

        add_action('admin_init', function () {
            register_setting(self::$optionsKey, self::$optionsKey);
        });

        $settingsControllerString = $this->getOptions()->isInitialized() ? 'controller.options.access' : 'controller.options.first-time';
        add_action('admin_menu', function () use ($settingsControllerString, $di, $options) {
            add_menu_page('iMoneza Settings', 'iMoneza', 'manage_options', 'imoneza', $di[$settingsControllerString],
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjAiIHk9IjAiIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwLCAwLCAxNTAsIDE1MCI+CiAgPGcgaWQ9IkxheWVyXzEiPgogICAgPGc+CiAgICAgIDxwYXRoIGQ9Ik0yNS44MzEsMTExLjc4NiBMNTQuOTcyLDY0LjcyIEw0OS41NjQsNjQuNzIgTDQ5LjU2NCw1MC41IEw3OC4zMDUsNTAuNSBMNzguMzA1LDEwMS4xNzEgTDEwMS41MzcsNjQuNzIgTDk3LjAzMSw2NC43MiBMOTcuNTMxLDUwLjUgTDEyNS4xNyw1MC41IEwxMjUuMTcsMTExLjc4NiBMMTMyLjE4LDExMS43ODYgTDEzMi4xOCwxMjUuNjA1IEwxMDYuNTQ0LDEyNS42MDUgTDEwNi41NDQsMTExLjc4NiBMMTExLjE1MSwxMTEuNzg2IEwxMTEuMTUxLDc1LjIzNSBMNzkuMjA2LDEyNS42MDUgTDY0LjQ4NSwxMjUuNjA1IEw2NC40ODUsNzUuMjM1IEw0Mi4xNTQsMTExLjc4NiBMNDguMzYzLDExMS43ODYgTDQ4LjM2MywxMjUuNjA1IEwxNy44MiwxMjUuNjA1IEwxNy44MiwxMTEuNzg2IHoiIGZpbGw9IiM0NTQ2NDMiLz4KICAgICAgPHBhdGggZD0iTTc1LjA1MywzNS40MDcgQzc1LjA1Myw0MS40ODcgNzAuMTI2LDQ2LjQxOSA2NC4wNDEsNDYuNDE5IEM1Ny45NjEsNDYuNDE5IDUzLjAzMiw0MS40ODcgNTMuMDMyLDM1LjQwNyBDNTMuMDMyLDI5LjMyNyA1Ny45NjEsMjQuMzk1IDY0LjA0MSwyNC4zOTUgQzcwLjEyNiwyNC4zOTUgNzUuMDUzLDI5LjMyNyA3NS4wNTMsMzUuNDA3IiBmaWxsPSIjNDU0NjQzIi8+CiAgICA8L2c+CiAgPC9nPgo8L3N2Zz4K'
                , 100);
            add_submenu_page('imoneza', 'iMoneza Settings', 'Access Settings', 'manage_options', 'imoneza');
            add_submenu_page(null, 'iMoneza Settings', 'Internal Config', 'manage_options', 'imoneza-config', $di['controller.options.internal']); // this is hidden
            
            // this is necessary because you can't use full URLs in add_submenu_page
            global $submenu;
            $url = $options->getManageUiUrl(Options::GET_DEFAULT);
            $submenu['imoneza'][] = ['Manage on iMoneza.com', 'edit_posts', $url];
        });
    }

    /**
     * Registers the admin ajax functionality
     */
    protected function registerAdminAjax()
    {
        $di = $this->di;
        
        add_action('wp_ajax_options_first_time', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\FirstTime $controller */
            $controller = $di['controller.options.first-time'];
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
        add_action('wp_ajax_options_internal', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\Internal $controller */
            $controller = $di['controller.options.internal'];
            $controller();
        });
    }
    
    /**
     * Add the admin scripts
     */
    protected function enqueueAdminScripts()
    {
        $di = $this->di;

        add_action('admin_enqueue_scripts', function () use ($di) {
            wp_register_style('imoneza-admin-css', $di['filter.asset-url']('css/admin.css'));
            wp_enqueue_style('imoneza-admin-css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('imoneza-admin-js', $di['filter.asset-url']('js/admin.js'), [], false, true);
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
        
        if (!$options->isInitialized()) {
            if (!($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'imoneza')) { // so if any page BUT the imoneza config page
                add_action('admin_notices', function() use ($di) {
                    if (current_user_can('manage_options')) {
                        $view = $di['view'];
                        $view->setView('admin/notify-config-needed');
                        echo $view();
                    }
                });
            }
        }
    }

    /**
     * Add the iMoneza box to the WordPress post page
     */
    protected function addPostMetaBox()
    {
        $options = $this->getOptions();
        $di = $this->di;

        add_action('add_meta_boxes', function() use ($di, $options) {
            $title = sprintf('<img src="%s">', $di['filter.asset-url']('images/logo-rectangle-small.png'));

            add_meta_box('imoneza-post-pricing', $title, function(\WP_Post $post) use ($di, $options) {
                $editing = !empty($post->ID);

                $pricingGroupSelected = '';
                if ($editing) {
                    $pricingGroupSelected = get_post_meta($post->ID, '_pricing-group-id', true);
                }
                if (empty($pricingGroupSelected)) {
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
                $service
                    ->setManagementApiKey($options->getManageApiKey())
                    ->setManagementApiSecret($options->getManageApiSecret())
                    ->setManageApiUrl($options->getManageApiUrl(Options::GET_DEFAULT))
                    ->setAccessApiUrl($options->getAccessApiUrl(Options::GET_DEFAULT));

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
     * Used to add the javascript to the page if necessary
     */
    protected function addClientSideAccessControl()
    {
        $options = $this->getOptions();
        $di = $this->di;
        add_action('wp_head', function() use ($di, $options) {
            if ($options->isAccessControlClient() && $options->getAccessApiKey()) {
                $view = $di['view'];
                
                $resourceKey = '';
                $post = is_single() ? get_post() : null;
                if ($post) {
                    $resourceKey = $di['filter.external-resource-key']($post);
                }
                
                $view->setView('client-side-access-header-js');
                $view->setData(['apiKey'=>$options->getAccessApiKey(), 'resourceKey'=>$resourceKey, 'javascriptUrl'=>$options->getJavascriptCdnUrl(Options::GET_DEFAULT)]);
                echo $view();
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
     * Actions to be taken when this is uninstalled
     */
    protected function registerDeactivationHooks()
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
            $controller(Controller\Options\RemoteRefresh::DO_NOT_SHOW_VIEW);

            if ($options->isDynamicallyCreateResources()) {
                // get all items that are un priced and only on the first page
                $query = $di['service.post']->getWPQueryPostsNotPriced();

                if ($query->have_posts()) {
                    $service = $di['service.imoneza'];
                    $service->setManagementApiKey($options->getManageApiKey())->setManagementApiSecret($options->getManageApiSecret());

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
        if ($this->getOptions()->isInitialized()) {
            if (wp_next_scheduled('imoneza_hourly') === false) wp_schedule_event(time(), 'hourly', 'imoneza_hourly');
        }
    }
}