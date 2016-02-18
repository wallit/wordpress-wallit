<?php
/**
 * Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\Service\iMoneza;
use iMonezaPRO\View;

/**
 * Class FirstTimeOptions
 * @package iMonezaPRO\Controller
 */
class FirstTimeOptions
{
    /**
     * Options constructor.
     */
    public function __construct()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'iMoneza'), 403);
        }
    }

    /**
     * Show Options items
     */
    public function __invoke()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $iMoneza = new iMoneza();

            $success = false;
            if ($iMoneza->noop()) {
                $success = true;
                update_option('imoneza-management-api-key', 'something');
                update_option('imoneza-management-api-secret', 'else');
            }
            View::render('options/first-time-json-response', ['success'=>$success]);
        }
        else {
            View::render('options/first-time');
        }
    }
}