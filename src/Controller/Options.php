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
class Options extends ControllerAbstract
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
        parent::__construct();
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
            $accessApiKey = trim($this->getPost('imoneza-access-api-key'));
            $accessApiSecret = trim($this->getPost('imoneza-access-api-secret'));
            $accessControl = trim($this->getPost('imoneza-access-control'));

            $errors = [];
            $this->iMonezaService
                ->setManagementApiKey($managementApiKey)
                ->setManagementApiSecret($managementApiSecret)
                ->setAccessApiKey($accessApiKey)
                ->setAccessApiSecret($accessApiSecret);

            if (!($propertyTitle = $this->iMonezaService->getPropertyTitle())) {
                $errors[] = $this->iMonezaService->getLastError();
            }
            if (!in_array($accessControl, ['S', 'C'])) {
                $errors[] = 'The access control somehow is not a valid value.';
            }
            if (!$this->iMonezaService->validateResourceAccessApiCredentials()) {
                $errors[] = $this->iMonezaService->getLastError();
            }

            $results = [
                'success'   =>  false,
                'data'  =>  [
                    'message'   =>  ''
                ]
            ];
            if (empty($errors)) {
                // do updates
                update_option('imoneza-management-api-key', $managementApiKey);
                update_option('imoneza-management-api-secret', $managementApiSecret);
                update_option('imoneza-access-api-key', $accessApiKey);
                update_option('imoneza-access-api-secret', $accessApiSecret);
                update_option('imoneza-property-title', $propertyTitle);
                update_option('imoneza-access-control', $accessControl);
                $results['success'] = true;
                $results['data']['message'] = 'Your settings have been saved!';
            }
            else {
                $results['success'] = false;
                $results['data']['message'] = array_reduce($errors, function($errorString, $error) {
                    if (empty($errorString)) {
                        $errorString = $error;
                    }
                    else {
                        $concatAdverbish = ['Also', 'Then', 'In addition'];
                        $errorString .= ' ' . $concatAdverbish[array_rand($concatAdverbish)] . ', ' . lcfirst($error);
                    }
                    return $errorString;
                });
            }

            View::render('options/json-response', $results);
        }
        else {
            $parameters = [
                'firstTimeSuccess' => boolval($this->getGet('first-time')),
                'propertyTitle' => get_option('imoneza-property-title'),
                'options' => [
                    'imoneza-management-api-key' => get_option('imoneza-management-api-key'),
                    'imoneza-management-api-secret' => get_option('imoneza-management-api-secret'),
                    'imoneza-access-api-key' => get_option('imoneza-access-api-key'),
                    'imoneza-access-api-secret' => get_option('imoneza-access-api-secret'),
                    'imoneza-access-control' => get_option('imoneza-access-control')
                ]
            ];

            View::render('options/dashboard', $parameters);
        }
    }
}