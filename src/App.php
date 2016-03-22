<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO;
use iMoneza\Exception;
use iMonezaPRO\Controller\RefreshOptions;
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

    public function __invoke()
    {
        $di = $this->di;
        $options = $this->getOptions();
        $firstTime = !$options->isInitialized();

        // this is scheduled hourly AFTER the first time we've kicked this off, or if we have this configured
        add_action('imoneza_hourly', function() use ($di) {
            /** @var \iMonezaPRO\Controller\RefreshOptions $controller */
            $controller = $di['controller.refresh-options'];
            $controller(RefreshOptions::DO_NOT_SHOW_VIEW);
        });

        register_activation_hook('imoneza-pro/imoneza-pro.php', function() use ($firstTime) {
            if (!$firstTime) wp_schedule_event(time(), 'hourly', 'imoneza_hourly');
        });
        register_deactivation_hook('imoneza-pro/imoneza-pro.php', function() {
            wp_clear_scheduled_hook('imoneza_hourly');
        });


        if (is_admin()) {
            if ($firstTime) {
                $this->addAdminNoticeConfigNeeded();
            }

            add_action('admin_init', function () {
                register_setting(self::$optionsKey, self::$optionsKey);
            });

            add_action('admin_menu', function () use ($firstTime, $di) {
                add_options_page('iMoneza Options', 'iMoneza', 'manage_options', self::SETTINGS_PAGE_IDENTIFIER, $firstTime ? $di['controller.first-time-options'] : $di['controller.options']);
            });

            add_action('add_meta_boxes', function() use ($options) {
                $title = sprintf('<img src="%s" style="height: 16px; vertical-align: middle">', WP_PLUGIN_URL . '/imoneza-pro/assets/images/logo-rectangle-small.png');

                add_meta_box('imoneza-post-pricing', $title, function(\WP_Post $post) use ($options) {
                    $editing = !empty($post->ID);

                    $pricingGroups = $options->getPricingGroups();
                    usort($pricingGroups, function($pricingGroupA, $pricingGroupB) {
                        return $pricingGroupA->isDefault() ? -1 : 1;
                    });
                    $pricingGroupSelected = $editing ? get_post_meta($post->ID, '_pricing-group-id', true) : $pricingGroups[0];
                    $overrideChecked = get_post_meta($post->ID, '_override-pricing', true);

                    View::render('post/post-pricing', [
                        'overrideChecked'   =>  $overrideChecked,
                        'dynamicallyCreateResources'=>$options->isDynamicallyCreateResources(),
                        'pricingGroupSelected'=>$pricingGroupSelected,
                        'pricingGroups'=>$pricingGroups
                    ]);
                }, 'post');
            });

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

            add_action('admin_enqueue_scripts', function () {
                wp_register_style('imoneza-admin-css', WP_PLUGIN_URL . '/imoneza-pro/assets/css/admin.css');
                wp_enqueue_style('imoneza-admin-css');
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-form');
                wp_enqueue_script('imoneza-admin-js', WP_PLUGIN_URL . '/imoneza-pro/assets/js/admin.js', [], false, true);
            });
        }
    }

    /**
     * Show the config if need be
     */
    public function addAdminNoticeConfigNeeded()
    {
        global $pagenow;

        if (!($pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == self::SETTINGS_PAGE_IDENTIFIER)) {
            add_action('admin_notices', function() {
                View::render('admin/notify-config-needed');
            });
        }
    }
}