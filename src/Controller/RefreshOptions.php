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
        $this->iMonezaService = $iMonezaService;
        //no parent constructor because this is also called by cron
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
            $options['property-title'] = $propertyTitle;
            update_option('imoneza-options', $options);
            $results['success'] = true;
            $results['data']['message'] = 'You have successfully refreshed your options.';
            $results['data']['title'] = $propertyTitle;
        }
        else {
            $results['success'] = false;
            $results['data']['message'] = $this->iMonezaService->getLastError();
        }
        error_log(var_export($results, true));
        View::render('options/json-response', $results);
    }
}