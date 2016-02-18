<?php
/**
 * View tool
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO;

/**
 * Class View
 * @package iMonezaPRO
 */
class View
{
    /**
     * Renders the view to the screen basically by including the view file
     * @param $view
     * @param array $params
     */
    public static function render($view, array $params = [])
    {
        /**
         * Helper for showing assets
         * @param $assetUrl
         * @return string
         */
        $assetUrl = function($assetUrl)
        {
            return WP_PLUGIN_URL . '/imoneza-pro/assets' . $assetUrl;
        };
        extract($params);

        require __DIR__ . '/View/' . $view . '.php';
    }
}