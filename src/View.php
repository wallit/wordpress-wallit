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
     */
    public static function render($view)
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

        require __DIR__ . '/View/' . $view . '.php';
    }
}