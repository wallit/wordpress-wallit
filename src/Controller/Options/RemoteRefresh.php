<?php
/**
 * Refresh Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Pro\Controller\Options;
use iMoneza\Library\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\Pro\Service\iMoneza;

/**
 * Class RemoteRefresh
 * @package iMoneza\WordPress\Pro\Controller\Options
 */
class RemoteRefresh extends ControllerAbstract
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
     * @var \Aura\View\View
     */
    protected $view;

    /**
     * Options constructor.
     * @param \Aura\View\View $view
     * @param iMoneza $iMonezaService
     */
    public function __construct(\Aura\View\View $view, iMoneza $iMonezaService)
    {
        $this->view = $view;
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
            $options->setPricingGroupsBubbleDefaultToTop($propertyOptions->getPricingGroups())
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
        if ($showView) {
            $view = $this->view;
            $view->setView('admin/options/json-response');
            $view->setData($results);
            echo $view();
        } 
    }
}