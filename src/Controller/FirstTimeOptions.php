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
     * @var iMoneza
     */
    protected $iMonezaService;

    /**
     * Options constructor.
     * @param iMoneza $iMonezaService
     */
    public function __construct(iMoneza $iMonezaService)
    {
        if (!current_user_can('manage_options')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'iMoneza'), 403);
        }

        $this->iMonezaService = $iMonezaService;
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

            $this->iMonezaService->setManagementApiKey($managementApiKey)->setManagementApiSecret($managementApiSecret);
            if ($propertyTitle = $this->iMonezaService->getPropertyTitle()) {
                update_option('imoneza-management-api-key', $managementApiKey);
                update_option('imoneza-management-api-secret', $managementApiSecret);
                update_option('imoneza-property-title', $propertyTitle);

                $results['success'] = true;
                $results['propertyTitle'] = $propertyTitle;
            }
            else {
                $results['success'] = false;
                $results['error'] = $this->iMonezaService->getLastError();
            }

            View::render('options/first-time-json-response', $results);
        }
        else {
            View::render('options/first-time');
        }
    }
}