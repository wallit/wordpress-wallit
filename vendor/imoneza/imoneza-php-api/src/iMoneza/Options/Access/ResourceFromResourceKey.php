<?php
/**
 * Get the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Access;

use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class ResourceFromResourceKey
 * @package iMoneza\Options\Access
 */
class ResourceFromResourceKey extends OptionsAbstract implements AccessInterface
{
    use ConfigurationTrait, AccessConfigurationTrait;

    /**
     * @var string the resource key (private because part of get params)
     */
    private $resourceKey = '';

    /**
     * @var string the url of this resource
     */
    protected $ResourceURL = '';

    /**
     * @var string the user token
     */
    protected $UserToken = '';

    /**
     * @var string the IP address
     */
    protected $IP = '';

    /**
     * @param mixed $resourceKey
     * @return $this
     */
    public function setResourceKey($resourceKey)
    {
        $this->resourceKey = $resourceKey;
        return $this;
    }

    /**
     * @param mixed $ResourceURL
     * @return $this
     */
    public function setResourceURL($ResourceURL)
    {
        $this->ResourceURL = $ResourceURL;
        return $this;
    }

    /**
     * @param mixed $UserToken
     * @return $this
     */
    public function setUserToken($UserToken)
    {
        $this->UserToken = $UserToken;
        return $this;
    }

    /**
     * @param mixed $IP
     * @return $this
     */
    public function setIP($IP)
    {
        $this->IP = $IP;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Resource/{$this->accessKey}/{$this->resourceKey}";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }
}