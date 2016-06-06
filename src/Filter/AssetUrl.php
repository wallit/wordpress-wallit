<?php
/**
 * Create URLs for assets for this plugin
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Filter;

/**
 * Class AssetUrl
 * @package iMoneza\WordPress\Filter
 */
class AssetUrl
{
    /**
     * @var string the plugin base url
     */
    protected $pluginBaseUrl = '';

    /**
     * AssetUrl constructor.
     * @param $pluginBaseUrl
     */
    public function __construct($pluginBaseUrl)
    {
        $this->pluginBaseUrl = $pluginBaseUrl;
    }

    /**
     * Filter the request to make it a valid URL
     * 
     * @param $assetUrl
     * @return string
     */
    public function __invoke($assetUrl)
    {
        return sprintf('%s/assets/%s', $this->pluginBaseUrl, $assetUrl);
    }
}