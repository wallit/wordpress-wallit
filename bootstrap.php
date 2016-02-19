<?php
/**
 * This file bootstraps the wordpress plugin
 */

require 'vendor/autoload.php';

if (class_exists('\Dotenv\Dotenv')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

if (is_admin()) {
    new \iMonezaPRO\Admin();
}