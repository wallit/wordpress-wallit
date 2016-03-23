<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO;
use iMoneza\Exception;
use iMonezaPRO\Controller\RefreshOptions;
use iMonezaPRO\Filter\ExternalResourceKey;
use Pimple\Container;
use iMonezaPRO\Traits;


/**
 * Class App
 * @package iMonezaPRO
 */
class App
{
    use Traits\Options;

    /**
     * @var string used to indicate it's the settings page
     */
    const SETTINGS_PAGE_IDENTIFIER = 'imoneza-pro-settings';

    /**
     * @var string the url for the client side js
     */
    const CLIENT_SIDE_JAVASCRIPT_URL = 'https://accessui.imoneza.com/assets/imoneza.js';

    /**
     * @var Container
     */
    protected $di;

    /**
     * App constructor.
     * @param Container $di
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * Invoke the APP
     */
    public function __invoke()
    {
        $this->registerActivationDeactivationHooks();
        $this->addCron();
        $this->addClientSideAccessControl();

        if (is_admin()) {
            $this->addAdminNoticeConfigNeeded();
            $this->initAdminItems();
            $this->addPostMetaBox();
            $this->addPublishPostAction();
            $this->registerAdminAjax();
            $this->enqueueAdminScripts();
        }
    }

    /**
     * Actions to be taken when this is installed / uninstalled
     */
    protected function registerActivationDeactivationHooks()
    {
        $options = $this->getOptions();

        register_activation_hook('imoneza-pro/imoneza-pro.php', function() use ($options) {
            if ($options->isInitialized()) wp_schedule_event(time(), 'hourly', 'imoneza_hourly');
        });
        register_deactivation_hook('imoneza-pro/imoneza-pro.php', function() {
            wp_clear_scheduled_hook('imoneza_hourly');
        });
    }

    /**
     * this is scheduled hourly AFTER the first time we've kicked this off, or if we have this configured
     *
     * It gets the options to keep them fresh, and then also checks for unpriced managed posts
     */
    protected function addCron()
    {
        $di = $this->di;
        $options = $this->getOptions();

        add_action('imoneza_hourly', function() use ($di, $options) {
            /** @var \iMonezaPRO\Controller\RefreshOptions $controller */
            $controller = $di['controller.refresh-options'];
            $controller(RefreshOptions::DO_NOT_SHOW_VIEW);

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
    }

    /**
     * Used to add the javascript to the page if necessary
     */
    protected function addClientSideAccessControl()
    {
        $options = $this->getOptions();
        add_action('wp_head', function() use ($options) {
            if ($options->isAccessControlClient() && $options->getAccessApiKey()) {
                $post = is_single() ? get_post() : null;
                if ($post) {
                    $filter = new ExternalResourceKey();
                    $js = self::CLIENT_SIDE_JAVASCRIPT_URL;
                    if ($overrideJs = getenv('CLIENT_SIDE_JAVASCRIPT_URL')) $js = $overrideJs;
                    View::render('header-js', ['apiKey'=>$options->getAccessApiKey(), 'resourceKey'=>$filter->filter($post), 'javascriptUrl'=>$js]);
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

        if (!$options->isInitialized()) {
            if (!($pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == self::SETTINGS_PAGE_IDENTIFIER)) {
                add_action('admin_notices', function() {
                    View::render('admin/notify-config-needed');
                });
            }
        }
    }

    /**
     * Add admin items like menu and and settings
     */
    protected function initAdminItems()
    {
        $options = $this->getOptions();
        $di = $this->di;

        add_action('admin_init', function () {
            register_setting(self::$optionsKey, self::$optionsKey);
        });

        add_action('admin_menu', function () use ($options, $di) {
            add_options_page('iMoneza Options', 'iMoneza', 'manage_options', self::SETTINGS_PAGE_IDENTIFIER, $options->isInitialized() ? $di['controller.options'] : $di['controller.first-time-options']);
        });
    }

    /**
     * Add the imoneza box to the WordPress post page
     */
    protected function addPostMetaBox()
    {
        $options = $this->getOptions();

        add_action('add_meta_boxes', function() use ($options) {
            $title = sprintf('<img src="%s" style="height: 16px; vertical-align: middle">', WP_PLUGIN_URL . '/imoneza-pro/assets/images/logo-rectangle-small.png');

            add_meta_box('imoneza-post-pricing', $title, function(\WP_Post $post) use ($options) {
                $editing = !empty($post->ID);

                $pricingGroupSelected = $editing ? get_post_meta($post->ID, '_pricing-group-id', true) : $options->getDefaultPricingGroup();
                $overrideChecked = get_post_meta($post->ID, '_override-pricing', true);

                View::render('post/post-pricing', [
                    'overrideChecked'   =>  $overrideChecked,
                    'dynamicallyCreateResources'=>$options->isDynamicallyCreateResources(),
                    'pricingGroupSelected'=>$pricingGroupSelected,
                    'pricingGroups'=>$options->getPricingGroups()
                ]);
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
                /** @var \iMonezaPRO\Service\iMoneza $service */
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

        add_action('wp_ajax_first-time-settings', function () use ($di) {
            /** @var \iMonezaPRO\Controller\FirstTimeOptions $controller */
            $controller = $di['controller.first-time-options'];
            $controller();
        });
        add_action('wp_ajax_settings', function () use ($di) {
            /** @var \iMonezaPRO\Controller\Options $controller */
            $controller = $di['controller.options'];
            $controller();
        });
        add_action('wp_ajax_refresh_settings', function () use ($di) {
            /** @var \iMonezaPRO\Controller\RefreshOptions $controller */
            $controller = $di['controller.refresh-options'];
            $controller();
        });
    }

    /**
     * Add the admin scripts
     */
    protected function enqueueAdminScripts()
    {
        add_action('admin_enqueue_scripts', function () {
            wp_register_style('imoneza-admin-css', WP_PLUGIN_URL . '/imoneza-pro/assets/css/admin.css');
            wp_enqueue_style('imoneza-admin-css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('imoneza-admin-js', WP_PLUGIN_URL . '/imoneza-pro/assets/js/admin.js', [], false, true);
        });
    }
}