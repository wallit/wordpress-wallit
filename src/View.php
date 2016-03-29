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
    public static $assetsRoot;
    
    /**
     * Renders the view to the screen basically by including the view file
     * @param $view
     * @param array $params
     */
    public static function render($view, array $params = [])
    {
        $assetsRoot = self::$assetsRoot;

        /**
         * Helper for showing assets
         * @param $assetUrl
         * @return string
         */
        $assetUrl = function($assetUrl) use ($assetsRoot)
        {
            return $assetsRoot . $assetUrl;
        };
        extract($params);

        require __DIR__ . '/View/' . $view . '.php';
    }
}