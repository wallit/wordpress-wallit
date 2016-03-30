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
$di['controller.options.pro-first-time'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\PROFirstTime($di['service.imoneza']);
};
$di['controller.options.access'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\Access($di['service.imoneza']);
};
$di['controller.options.remote-refresh'] = function($di) {
	return new \iMoneza\WordPress\Controller\Options\RemoteRefresh($di['service.imoneza']);
};

/**
 * Run the proper "app"
 */
$app = new \iMoneza\WordPress\PRO($di);
$app();