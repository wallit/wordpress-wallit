<?php
/**
 * This file bootstraps the wordpress plugin
 */

require 'vendor/autoload.php';

if (is_admin()) {
    new \iMonezaPRO\Admin();
}