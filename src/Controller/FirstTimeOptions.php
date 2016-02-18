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
class FirstTimeOptions extends ControllerAbstract
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
        if ($this->isPost()) {
            $managementApiKey = trim($this->getPost('imoneza-management-api-key'));
            $managementApiSecret = trim($this->getPost('imoneza-management-api-secret'));
            $results = [];

            $iMoneza = new iMoneza($managementApiKey, $managementApiSecret);
            if ($propertyTitle = $iMoneza->getPropertyTitle()) {
                update_option('imoneza-management-api-key', $managementApiKey);
                update_option('imoneza-management-api-secret', $managementApiSecret);
                update_option('imoneza-property-title', $propertyTitle);

                $results['success'] = true;
                $results['propertyTitle'] = $propertyTitle;
            }
            else {
                $results['success'] = false;
                $results['error'] = "Uh oh! Something's wrong... check your API Key and Secret.  If those look fine, you should contact us.";
            }

            View::render('options/first-time-json-response', $results);
        }
        else {
            View::render('options/first-time');
        }
    }
}