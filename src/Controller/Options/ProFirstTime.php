<?php
/**
 * First Time Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\Model\Options;
use iMoneza\WordPress\Service\iMoneza;
use iMoneza\WordPress\View;

/**
 * Class ProFirstTime
 * @package iMoneza\WordPress\Controller\Options
 */
class ProFirstTime extends ControllerAbstract
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

            View::render('admin/options/json-response', $results);
        }
        else {
            View::render('admin/options/pro-first-time');
        }
    }
}