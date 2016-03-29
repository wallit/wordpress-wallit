<?php
/*
Plugin Name: iMoneza PRO
Plugin URI: https://github.com/iMoneza/wordpress-imoneza-pro
Description: This plugin lets you provide paid access control mechanisms on your WordPress by using iMoneza.
Author: iMoneza
Version: 2.0.0
*/

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	die('Please do not surf to this file directly.');
}

/** Get autoloader */
require 'vendor/autoload.php';

/********************** DI *******************************************/
$di = new \Pimple\Container();

// DI Services
$di['service.imoneza'] = function () {
	return new \iMoneza\WordPress\Service\iMoneza();
};

// DI Controllers
$di['controller.first-time-options'] = function($di) {
	return new \iMoneza\WordPress\Controller\PRO\FirstTimeOptions($di['service.imoneza']);
};
$di['controller.options'] = function($di) {
	return new \iMoneza\WordPress\Controller\PRO\Options($di['service.imoneza']);
};
$di['controller.refresh-options'] = function($di) {
	return new \iMoneza\WordPress\Controller\PRO\RefreshOptions($di['service.imoneza']);
};

// configure assets root
\iMoneza\WordPress\View::$assetsRoot = sprintf('%s/%s/assets', WP_PLUGIN_URL, basename(__DIR__));

/**
 * Run the proper "app"
 */
$app = new \iMoneza\WordPress\PRO($di);
$app();