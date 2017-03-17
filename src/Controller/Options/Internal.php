<?php
/**
 * Internal Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;

/**
 * Class Internal
 * @package iMoneza\WordPress\Controller\Options
 */
class Internal extends ControllerAbstract
{
    /**
     * Internal constructor.
     * 
     * @param \Aura\View\View $view
     */
    public function __construct(\Aura\View\View $view)
    {
        parent::__construct($view);
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

            $postOptions = array_map('trim', $this->getPost('imoneza-options', []));
            $options->setManageApiUrl($postOptions['manage-api-url'])
                ->setAccessApiUrl($postOptions['access-api-url'])
                ->setJavascriptCdnUrl($postOptions['javascript-cdn-url'])
                ->setManageUiUrl($postOptions['manage-ui-url']);
              
            $this->saveOptions($options);

            $results = $this->getGenericAjaxResultsObject();
            $results['success'] = true;
            $results['data']['message'] = __('Your settings have been saved!', 'iMoneza');

            $view->setView('admin/options/json-response');
            $view->setData($results);
        }
        else {
            $view->setView('admin/options/internal');
            $view->setData(['options'=>$options]);
        }

        echo $view();
    }
}