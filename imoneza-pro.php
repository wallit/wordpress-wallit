<?php
/**
 * Plugin Name: iMoneza PRO
 * Plugin URI: https://github.com/iMoneza/wordpress-imoneza-pro
 * Description: This plugin lets you provide paid access control mechanisms on your WordPress by using iMoneza.
 * Author: iMoneza
 * Author URI: http://imoneza.com
 * Version: 2.0.0
 * License: GPLv3
 */

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	die('Please do not surf to this file directly.');
}

add_action('plugins_loaded', function() {
	if (stream_resolve_include_path('vendor/autoload.php')) {
		require_once 'vendor/autoload.php';

		if (class_exists('iMoneza\\WordPress\\Pro\\App')) {
			$app = new \iMoneza\WordPress\Pro\App();
			$app();
		}
		else {
			/**
			 * add a setting link to remind them to run config
			 */
			$pluginId = plugin_dir_path(__FILE__);
			add_filter('plugin_action_links_' . $pluginId, function($links) use ($pluginId) {
				$links['run-composer-update'] = sprintf(
					'<a href="%s">%s</a>',
					admin_url('plugins.php?page=composer-manager-composer-install&plugin=' . $pluginId),
					__('Run Composer Update', 'imoneza')
				);
				return $links;
			});
		}
	}
	else {
		add_action('admin_notices', function() {
			echo '<div class="notice notice-error"><p>';
			echo __('This plugin relies on WP Composer Manager.  Please install that plugin, then use it to update this plugin.', 'imoneza');
			echo '</p></div>';
		});
	}
});