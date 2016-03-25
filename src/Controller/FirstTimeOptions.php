<?php
/**
 * Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\Model\Options;
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
            check_ajax_referer('imoneza-settings');

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
            View::render('admin/options/first-time');
        }
    }
}