<?php
/**
 * Display Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\View;

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
        $options = $this->getOptions();
        $indicatorClasses = ['dashicons dashicons-star-filled', 'dashicons dashicons-awards', 'dashicons dashicons-thumbs-up'];
        
        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postOptions = array_filter($this->getPost('imoneza-options', []), 'trim');
            $options->setIndicatePremiumContent(boolval($postOptions['indicate-premium-content']));

            // since this is radio, I'm not going to verify and error handle - just ignore if they don't belong
            if (in_array($postOptions['indicator-class'], $indicatorClasses)) {
                $options->setPremiumIndicatorIconClass($postOptions['indicator-class']);
            }
            $this->saveOptions($options);

            $results = $this->getGenericAjaxResultsObject();
            $results['success'] = true;
            $results['data']['message'] = 'Your settings have been saved!';

            View::render('admin/options/json-response', $results);
        }
        else {
            View::render('admin/options/display', ['options'=>$options, 'indicatorClasses'=>$indicatorClasses, 'isPro'=>$this->isPro()]);
        }
    }
}