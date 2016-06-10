<?php
/**
 * Refresh Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\Model\Options;
use iMoneza\WordPress\Service;

/**
 * Class RemoteRefresh
 * @package iMoneza\WordPress\Controller\Options
 */
class RemoteRefresh extends ControllerAbstract
{
    /**
     * @var boolean do not show a view
     */
    const DO_NOT_SHOW_VIEW = false;

    /**
     * @var Service\iMoneza
     */
    protected $iMonezaService;

    /**
     * Options constructor.
     * 
     * @param \Aura\View\View $view
     * @param Service\iMoneza $iMonezaService
     */
    public function __construct(\Aura\View\View $view, Service\iMoneza $iMonezaService)
    {
        parent::__construct($view);
        $this->iMonezaService = $iMonezaService;
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
            ->setManagementApiKey($options->getManageApiKey())
            ->setManagementApiSecret($options->getManageApiSecret())
            ->setManageApiUrl($options->getManageApiUrl(Options::GET_DEFAULT))
            ->setAccessApiUrl($options->getAccessApiUrl(Options::GET_DEFAULT));
        
        if ($propertyOptions = $this->iMonezaService->getProperty()) {
            $options->setPricingGroupsBubbleDefaultToTop($propertyOptions->getPricingGroups())
                ->setDynamicallyCreateResources($propertyOptions->isDynamicallyCreateResources())
                ->setPropertyTitle($propertyOptions->getTitle());
            $this->saveOptions($options);

            $results['success'] = true;
            $results['data']['message'] = __('You have successfully refreshed your options.', 'iMoneza');
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