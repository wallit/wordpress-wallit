<?php
/**
 * Refresh Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\Service\iMoneza;
use iMonezaPRO\View;

/**
 * Class RefreshOptions
 * @package iMonezaPRO\Controller
 */
class RefreshOptions extends ControllerAbstract
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
        $options = $this->getOptions();
        $results = $this->getGenericAjaxResultsObject();

        $this->iMonezaService
            ->setManagementApiKey($options['management-api-key'])
            ->setManagementApiSecret($options['management-api-secret']);

        if ($propertyTitle = $this->iMonezaService->getPropertyTitle()) {
            $results['success'] = true;
            $results['data']['message'] = 'You have successfully refreshed your options.';
            $results['data']['title'] = $propertyTitle;
        }
        else {
            $results['success'] = false;
            $results['data']['message'] = $this->iMonezaService->getLastError();
        }
        View::render('options/json-response', $results);
    }
}