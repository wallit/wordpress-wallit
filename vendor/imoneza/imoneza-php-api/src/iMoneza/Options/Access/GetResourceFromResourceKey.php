<?php
/**
 * Get the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Access;

use iMoneza\Options\ConfigurationTrait;

/**
 * Class GetResourceFromResourceKey
 * @package iMoneza\Options\Access
 */
class GetResourceFromResourceKey extends AccessConfigurationAbstract
{
    use ConfigurationTrait;

    /**
     * @var string the resource key (private because part of get params)
     */
    private $resourceKey = '';
    
    /**
     * @var string the user token
     */
    protected $UserToken = '';
    
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
     * @param mixed $UserToken
     * @return $this
     */
    public function setUserToken($UserToken)
    {
        $this->UserToken = $UserToken;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Resource/{$this->accessKey}/{$this->resourceKey}";
    }
}