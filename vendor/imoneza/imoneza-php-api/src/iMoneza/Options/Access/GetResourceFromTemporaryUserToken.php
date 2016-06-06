<?php
/**
 * Get the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Access;

use iMoneza\Options\ConfigurationTrait;

/**
 * Class GetResourceFromTemporaryUserToken
 * @package iMoneza\Options\Access
 */
class GetResourceFromTemporaryUserToken extends AccessConfigurationAbstract
{
    use ConfigurationTrait;

    /**
     * @var string the user token
     */
    protected $temporaryUserToken = '';

    /**
     * @var string the resource key
     */
    protected $ResourceKey = '';

    /**
     * @param mixed $resourceKey
     * @return $this
     */
    public function setResourceKey($resourceKey)
    {
        $this->ResourceKey = $resourceKey;
        return $this;
    }
    
    /**
     * @param string $temporaryUserToken
     * @return $this
     */
    public function setTemporaryUserToken($temporaryUserToken)
    {
        $this->temporaryUserToken = $temporaryUserToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/TemporaryUserToken/{$this->accessKey}/{$this->temporaryUserToken}";
    }

}