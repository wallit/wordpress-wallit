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
        $options = get_option('imoneza-options');

        if ($this->isPost()) {
            $postOptions = array_filter($this->getPost('imoneza-options', []), 'trim');

            $errors = [];
            $this->iMonezaService
                ->setManagementApiKey($postOptions['management-api-key'])
                ->setManagementApiSecret($postOptions['management-api-secret'])
                ->setAccessApiKey($postOptions['access-api-key'])
                ->setAccessApiSecret($postOptions['access-api-secret']);

            if (!($propertyTitle = $this->iMonezaService->getPropertyTitle())) {
                $errors[] = $this->iMonezaService->getLastError();
            }
            if (!in_array($postOptions['access-control'], ['S', 'C'])) {
                $errors[] = 'The access control somehow is not a valid value.';
            }
            if (!$this->iMonezaService->validateResourceAccessApiCredentials()) {
                $errors[] = $this->iMonezaService->getLastError();
            }

            $results = $this->getGenericAjaxResultsObject();

            if (empty($errors)) {
                $options = array_merge($options, $postOptions);
                update_option('imoneza-options', $options);
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
                'options' => $options
            ];

            View::render('options/dashboard', $parameters);
        }
    }
}