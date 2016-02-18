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
 * Class Options
 * @package iMonezaPRO\Controller
 */
class Options
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
        View::render('options/dashboard');
    }
}