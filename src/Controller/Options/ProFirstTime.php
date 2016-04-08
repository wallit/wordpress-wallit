<?php
/**
 * First Time Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Pro\Controller\Options;
use iMoneza\WordPress\Pro\Controller\ControllerAbstract;
use iMoneza\WordPress\Pro\Model\Options;
use iMoneza\WordPress\Pro\Service\iMoneza;

/**
 * Class ProFirstTime
 * @package iMoneza\WordPress\Pro\Controller\Options
 */
class ProFirstTime extends ControllerAbstract
{
    /**
     * @var iMoneza
     */
    protected $iMonezaService;

    /**
     * Options constructor.
     * @param \Aura\View\View $view
     * @param iMoneza $iMonezaService
     */
    public function __construct(\Aura\View\View $view, iMoneza $iMonezaService)
    {
        parent::__construct($view);
        $this->iMonezaService = $iMonezaService;
    }

    /**
     * Show Options items
     */
    public function __invoke()
    {
        $view = $this->view;

        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postedOptions = array_filter($this->getPost('imoneza-options', []), 'trim');
            $results = $this->getGenericAjaxResultsObject();

            $this->iMonezaService->setManagementApiKey($postedOptions['management-api-key'])->setManagementApiSecret($postedOptions['management-api-secret']);
            if ($propertyOptions = $this->iMonezaService->getProperty()) {
                $options = $this->getOptions();
                $options->setManagementApiKey($postedOptions['management-api-key'])
                    ->setManagementApiSecret($postedOptions['management-api-secret'])
                    ->setPropertyTitle($propertyOptions->getTitle())
                    ->setDynamicallyCreateResources($propertyOptions->isDynamicallyCreateResources())
                    ->setAccessControl(Options::ACCESS_CONTROL_CLIENT)
                    ->setPricingGroupsBubbleDefaultToTop($propertyOptions->getPricingGroups());
                $this->saveOptions($options);

                wp_schedule_event(strtotime('+15 minutes'), 'hourly', 'imoneza_hourly');
                $results['success'] = true;
            }
            else {
                $results['success'] = false;
                $results['data']['message'] = $this->iMonezaService->getLastError();
            }

            $view->setData($results);
            $view->setView('admin/options/json-response');
        }
        else {
            $view->setView('admin/options/pro-first-time');
        }

        echo $view();
    }
}