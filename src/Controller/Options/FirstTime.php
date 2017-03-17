<?php
/**
 * First Time Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\Model;
use iMoneza\WordPress\Service;

/**
 * Class FirstTime
 * @package iMoneza\WordPress\Controller\Options
 */
class FirstTime extends ControllerAbstract
{
    /**
     * @var Service\iMoneza
     */
    protected $iMonezaService;

    /**
     * Options constructor.
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
     */
    public function __invoke()
    {
        $this->verifyCapabilities();
        $view = $this->view;
        $options = $this->getOptions();

        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postedOptions = array_map('trim', $this->getPost('imoneza-options', []));
            $results = $this->getGenericAjaxResultsObject();

            $this->iMonezaService
                ->setManagementApiKey($postedOptions['management-api-key'])
                ->setManagementApiSecret($postedOptions['management-api-secret'])
                ->setManageApiUrl($options->getManageApiUrl(Model\Options::GET_DEFAULT))
                ->setAccessApiUrl($options->getAccessApiUrl(Model\Options::GET_DEFAULT));

            if ($propertyOptions = $this->iMonezaService->getProperty()) {
                $options->setManageApiKey($postedOptions['management-api-key'])
                    ->setManageApiSecret($postedOptions['management-api-secret'])
                    ->setPropertyTitle($propertyOptions->getTitle())
                    ->setDynamicallyCreateResources($propertyOptions->isDynamicallyCreateResources())
                    ->setAccessControl(Model\Options::ACCESS_CONTROL_CLIENT)
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
            $view->setView('admin/options/first-time');
        }

        echo $view();
    }
}