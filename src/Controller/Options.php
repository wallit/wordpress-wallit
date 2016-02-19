<?php
/**
 * Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\View;

/**
 * Class Options
 * @package iMonezaPRO\Controller
 */
class Options extends ControllerAbstract
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
        $parameters = [
            'firstTimeSuccess'  =>  boolval($this->getGet('first-time')),
            'propertyTitle'     =>  get_option('imoneza-property-title'),
            'options'   =>  [
                'imoneza-management-api-key'    =>  get_option('imoneza-management-api-key'),
                'imoneza-management-api-secret' =>  get_option('imoneza-management-api-secret')
            ]
        ];

        View::render('options/dashboard', $parameters);
    }
}