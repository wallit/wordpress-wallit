<?php
/**
 * View tool
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress;

/**
 * Class View
 * @package iMoneza\WordPress
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
            $assetsRoot = sprintf('%s/%s/assets', WP_PLUGIN_URL, basename(realpath(__DIR__ . '/../')));
            return $assetsRoot . $assetUrl;
        };
        extract($params);

        require __DIR__ . '/View/' . $view . '.php';
    }
}