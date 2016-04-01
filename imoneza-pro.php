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

/** Get autoloader */
require 'vendor/autoload.php';

/********************** DI *******************************************/
$di = new \Pimple\Container();

// DI Services
$di['service.imoneza'] = function () {
	return new \iMoneza\WordPress\Service\iMoneza();
};

// DI Controllers
$di['controller.options.pro-first-time'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\ProFirstTime($di['service.imoneza']);
};
$di['controller.options.access'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\Access($di['service.imoneza']);
};
$di['controller.options.remote-refresh'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\RemoteRefresh($di['service.imoneza']);
};
$di['controller.options.display'] = function() {
	return new \iMoneza\WordPress\Controller\Options\Display();
};

/**
 * Run the proper "app"
 */
$app = new \iMoneza\WordPress\Pro($di);
$app();