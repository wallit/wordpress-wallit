<?php
/**
 * Shared "private" configuration options
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options;

/**
 * Class ConfigurationTrait
 * @package iMoneza\Options
 */
trait ConfigurationTrait
{
    /**
     * @var string the access key for this request
     */
    private $accessKey = '';

    /**
     * Gets the base URL
     * @return string
     */
    public function getApiBaseURL()
    {
        return $this->apiBaseUrl;
    }

    /**
     * Set the URL
     *
     * @param $baseUrl
     * @return $this
     */
    public function setApiBaseURL($baseUrl)
    {
        $this->apiBaseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param string $accessKey
     * @return $this
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAccessKey()
    {
        return !empty($this->accessKey);
    }

}