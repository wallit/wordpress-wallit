<?php
/**
 * This file bootstraps the wordpress plugin
 */

/** Get autoloader */
require 'vendor/autoload.php';

/** for development, I use env files.  You shouldn't need to worry about this */
if (class_exists('\Dotenv\Dotenv')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

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
if (is_admin()) {
    new \iMonezaPRO\Admin($di);
}