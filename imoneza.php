<?php
/**
 * Plugin Name: iMoneza
 * Plugin URI: https://github.com/iMoneza/wordpress-imoneza
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

require_once 'vendor/autoload.php';
$app = new \iMoneza\WordPress\App(__DIR__);
$app();
