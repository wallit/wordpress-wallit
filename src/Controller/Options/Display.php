<?php
/**
 * Options controller
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
        if ($this->isPro()) {
            $indicatorClasses = array_merge($indicatorClasses, ['dashicons dashicons-admin-post', 'dashicons dashicons-admin-network', 'dashicons dashicons-lock', 'dashicons dashicons-megaphone', 'dashicons dashicons-flag']);
        }

        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postOptions = array_filter($this->getPost('imoneza-options', []), 'trim');

            $options->setIndicatePremiumContent(boolval($postOptions['indicate-premium-content']));

            $validationArray = $this->isPro() ? array_merge($indicatorClasses, ['imoneza-custom-indicator']) : $indicatorClasses;
            if (in_array($postOptions['indicator-class'], $validationArray)) {
                $options->setPremiumIndicatorIconClass($postOptions['indicator-class']);
            }
            else {
                $options->setPremiumIndicatorIconClass($indicatorClasses[0]); // to unset if it was set invalid
            }
            if ($this->isPro()) {
                $options->setPremiumIndicatorCustomText($postOptions['indicator-text'])
                    ->setPremiumIndicatorCustomColor($postOptions['indicator-color']);
            }

            $options->setNotifyAdblocker(boolval($postOptions['notify-adblocker']))
                ->setAdblockNotification(sanitize_text_field($postOptions['adblock-notification']));

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