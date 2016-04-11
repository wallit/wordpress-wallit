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

/** if standard is installed, deactivate it */
register_activation_hook('imoneza-pro/imoneza-pro.php', function() {
	if (is_plugin_active('imoneza/imoneza.php')) {
		deactivate_plugins('imoneza/imoneza.php');
	}
});

add_action('plugins_loaded', function() {
	if (stream_resolve_include_path('vendor/autoload.php')) {
		require_once 'vendor/autoload.php';

		if (class_exists('iMoneza\\WordPress\\Pro\\Pro')) {
			$app = new \iMoneza\WordPress\Pro\Pro(__DIR__);
			$app();
		}
		else {
			/**
			 * add a setting link to remind them to run config
			 */
			$pluginId = plugin_basename(__FILE__);
			add_filter('plugin_action_links_' . $pluginId, function($links) use ($pluginId) {
				$links['run-composer-update'] = sprintf(
					'<a href="%s">%s</a>',
					admin_url('plugins.php?page=composer-manager'),
					__('WP Composer Manager', 'imoneza')
				);
				return $links;
			});

			add_action('admin_notices', function () use ($pluginId) {
				echo '<div class="notice notice-error"><p>';
				echo __('The iMoneza PRO plugin needs composer update to be ran. ', 'imoneza');
				echo sprintf('<a href="%s">%s</a>',
					admin_url('plugins.php?page=composer-manager-composer-update&plugin=' . $pluginId),
					__('Please click here to run the command using WP Composer Manager.', 'imoneza')
				);
				echo " " . __('Please note - this may take a while based on your internet connection.');
				echo '</p></div>';
			});
		}
	}
	else {
		add_action('admin_notices', function() {
			echo '<div class="notice notice-error"><p>';
			echo __('The iMoneza PRO plugin relies on WP Composer Manager.  Please install that plugin, then use it to update this plugin.', 'imoneza');
			echo '</p></div>';
		});
	}
});