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
     * @var boolean do not show a view
     */
    const DO_NOT_SHOW_VIEW = false;

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
     * @param bool $showView
     */
    public function __invoke($showView = true)
    {
        $options = $this->getOptions();
        $results = $this->getGenericAjaxResultsObject();

        $this->iMonezaService
            ->setManagementApiKey($options->getManagementApiKey())
            ->setManagementApiSecret($options->getManagementApiSecret());

        if ($propertyOptions = $this->iMonezaService->getProperty()) {
            $options->setPricingGroups($propertyOptions->getPricingGroups())
                ->setDynamicallyCreateResources($propertyOptions->isDynamicallyCreateResources())
                ->setPropertyTitle($propertyOptions->getTitle());
            $this->saveOptions($options);

            $results['success'] = true;
            $results['data']['message'] = 'You have successfully refreshed your options.';
            $results['data']['options'] = $options;
        }
        else {
            $results['success'] = false;
            $results['data']['message'] = $this->iMonezaService->getLastError();
            if (!$showView) error_log($this->iMonezaService->getLastError());
        }
        if ($showView) View::render('admin/options/json-response', $results);
    }
}