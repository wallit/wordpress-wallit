<?php
/**
 * Plugin Name: iMoneza
 * Plugin URI: https://github.com/iMoneza/wordpress-imoneza
 * Description: Integrate your WordPress site with iMoneza.
 * Author: iMoneza
 * Author URI: http://imoneza.com
 * Version: 2.1.4
 * License: GPLv3
 * Text Domain: iMoneza
 */

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	die('Please do not surf to this file directly.');
}

require_once 'vendor/autoload.php';

if (is_admin()) {
	new \WPOG\UpdateWatcher(__FILE__, 'iMoneza', 'wordpress-imoneza');
}

$app = new \iMoneza\WordPress\App(__DIR__);
$app();
