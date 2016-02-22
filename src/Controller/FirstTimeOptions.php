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
        parent::__construct();
        $this->iMonezaService = $iMonezaService;
    }

    /**
     * Show Options items
     */
    public function __invoke()
    {
        if ($this->isPost()) {
            $options = array_filter($this->getPost('imoneza-options', []), 'trim');
            $results = $this->getGenericAjaxResultsObject();

            $this->iMonezaService->setManagementApiKey($options['management-api-key'])->setManagementApiSecret($options['management-api-secret']);
            if ($propertyTitle = $this->iMonezaService->getPropertyTitle()) {
                $firstTimeOptions = [
                    'management-api-key'    =>  $options['management-api-key'],
                    'management-api-secret' => $options['management-api-secret'],
                    'property-title'    =>  $propertyTitle,
                    'access-control'    =>  'C'
                ];
                update_option('imoneza-options', $firstTimeOptions);
                $results['success'] = true;
            }
            else {
                $results['success'] = false;
                $results['data']['message'] = $this->iMonezaService->getLastError();
            }

            View::render('options/json-response', $results);
        }
        else {
            View::render('options/first-time');
        }
    }
}