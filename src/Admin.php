<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO;

/**
 * Class Admin
 * @package iMonezaPRO
 */
class Admin
{
    /**
     * @var string used to indicate it's the settings page
     */
    const SETTINGS_PAGE_IDENTIFIER = 'imoneza-pro-settings';

    /**
     * App constructor.
     */
    public function __construct()
    {
        $firstTime = empty(get_option('imoneza-management-api-key'));

        if ($firstTime) {
            $this->addAdminNoticeConfigNeeded();
        }

        add_action('admin_init', function() {
            register_setting('imoneza-settings', 'imoneza-management-api-key');
            register_setting('imoneza-settings', 'imoneza-management-api-secret');
            register_setting('imoneza-settings', 'imoneza-property-title');

        });

        add_action('admin_menu', function() use ($firstTime) {
            add_options_page('iMoneza Options', 'iMoneza', 'manage_options', self::SETTINGS_PAGE_IDENTIFIER, $firstTime ? new Controller\FirstTimeOptions() : new Controller\Options());
        });
        add_action('wp_ajax_first-time-settings', function() {
            $controller = new Controller\FirstTimeOptions();
            $controller();
        });

        add_action('admin_enqueue_scripts', function() {
            wp_register_style('imoneza-admin-css', WP_PLUGIN_URL . '/imoneza-pro/assets/css/admin.css');
            wp_enqueue_style('imoneza-admin-css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('imoneza-admin-js', WP_PLUGIN_URL . '/imoneza-pro/assets/js/admin.js', [], false, true);
        });

    }

    /**
     * Show the config if need be
     */
    public function addAdminNoticeConfigNeeded()
    {
        global $pagenow;

        if (!($pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == self::SETTINGS_PAGE_IDENTIFIER)) {
            add_action('admin_notices', function() {
                View::render('notify-config-needed');
            });
        }
    }
}