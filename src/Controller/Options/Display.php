<?php
/**
 * Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\Library\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;

/**
 * Class Display
 * @package iMoneza\WordPress\Controller\Options
 */
class Display extends ControllerAbstract
{
    /**
     * Show Options items
     */
    public function __invoke()
    {
        $view = $this->view;

        $options = $this->getOptions();
        $indicatorClasses = ['dashicons dashicons-star-filled', 'dashicons dashicons-awards', 'dashicons dashicons-thumbs-up', 'dashicons dashicons-admin-post', 'dashicons dashicons-admin-network', 'dashicons dashicons-lock', 'dashicons dashicons-megaphone', 'dashicons dashicons-flag'];

        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postOptions = array_filter($this->getPost('imoneza-options', []), 'trim');

            $options->setIndicatePremiumContent(boolval($postOptions['indicate-premium-content']));

            $validationArray = array_merge($indicatorClasses, ['imoneza-custom-indicator']);
            if (in_array($postOptions['indicator-class'], $validationArray)) {
                $options->setPremiumIndicatorIconClass($postOptions['indicator-class']);
            }
            else {
                $options->setPremiumIndicatorIconClass($indicatorClasses[0]); // to unset if it was set invalid
            }
            $options->setPremiumIndicatorCustomText($postOptions['indicator-text'])
                ->setPremiumIndicatorCustomColor($postOptions['indicator-color']);
            
            $this->saveOptions($options);

            $results = $this->getGenericAjaxResultsObject();
            $results['success'] = true;
            $results['data']['message'] = 'Your settings have been saved!';

            $view->setView('admin/options/json-response');
            $view->setData($results);
        }
        else {
            $view->setView('admin/options/display');
            $view->setData(['options'=>$options, 'indicatorClasses'=>$indicatorClasses]);
        }

        echo $view();
    }
}