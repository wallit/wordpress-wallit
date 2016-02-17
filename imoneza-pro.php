<?php
/*
Plugin Name: iMoneza PRO
Plugin URI: https://www.imoneza.com/wordpress-plugin/
Description: This plugin lets you provide paid access control mechanisms on your WordPress by using iMoneza.
Author: iMoneza
Version: 2.0.0
*/

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	die('Please do not surf to this file directly.');
}

require_once 'bootstrap.php';


//// Make sure we don't expose any info if called directly
//if ( !function_exists( 'add_action' ) ) {
//	echo 'Please visit the iMoneza website for more details on using this plugin.';
//	exit;
//}
//
//define('IMONEZA__PLUGIN_URL', plugin_dir_url( __FILE__ ));
//define('IMONEZA__PLUGIN_DIR', plugin_dir_path( __FILE__ ));
//define('IMONEZA__DEBUG', FALSE);
//
//define('IMONEZA__RA_API_URL', 'https://accessapi.imoneza.com');
//define('IMONEZA__RM_API_URL', 'https://manageapi.imoneza.com');
//define('IMONEZA__RA_UI_URL', 'https://accessui.imoneza.com');
//
//require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza.php');
//require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza-api.php');
//require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza-resourceaccess.php');
//require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza-restfulrequest.php');
//$imoneza = new iMoneza();
//
//if (is_admin()) {
//	require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza-admin.php');
//    require_once(IMONEZA__PLUGIN_DIR . 'class.imoneza-resourcemanagement.php');
//    $imoneza_admin = new iMoneza_Admin();
//}
