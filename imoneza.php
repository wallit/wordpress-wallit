<?php
/**
 * Plugin Name: iMoneza
 * Plugin URI: https://github.com/iMoneza/wordpress-imoneza
 * Description: Integrate your WordPress site with iMoneza.
 * Author: iMoneza
 * Author URI: http://imoneza.com
 * Version: 2.1.0
 * License: GPLv3
 */

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	die('Please do not surf to this file directly.');
}

require_once 'vendor/autoload.php';

/** only used for development */
if (class_exists('Dotenv\Dotenv')) {
	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();
}

$app = new \iMoneza\WordPress\App(__DIR__);
$app();
