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
	return new \iMonezaPRO\Service\iMoneza();
};

// DI Controllers
$di['controller.first-time-options'] = function($di) {
	return new \iMonezaPRO\Controller\FirstTimeOptions($di['service.imoneza']);
};
$di['controller.options'] = function($di) {
	return new \iMonezaPRO\Controller\Options($di['service.imoneza']);
};
$di['controller.refresh-options'] = function($di) {
	return new \iMonezaPRO\Controller\RefreshOptions($di['service.imoneza']);
};

/**
 * Run the proper "app"
 */
$app = new \iMonezaPRO\App($di);
$app();