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
     * App constructor.
     */
    public function __construct()
    {
        $this->addAdminNoticeConfigNeeded();

        add_action('admin_menu', function() {
            add_options_page('iMoneza Options', 'iMoneza', 'manage_options', 'imoneza-pro', new Controller\FirstTimeOptions());
        });

        add_action('admin_enqueue_scripts', function() {
            wp_register_style('imoneza-admin-css', WP_PLUGIN_URL . '/imoneza-pro/assets/css/admin.css');
            wp_enqueue_style('imoneza-admin-css');
        });

    }

    /**
     * Show the config if need be
     */
    public function addAdminNoticeConfigNeeded()
    {
        global $pagenow;

        if (!($pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == 'imoneza-pro')) {
            add_action('admin_notices', function() {
                View::render('notify-config-needed');
            });
        }
    }
}