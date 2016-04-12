<?php
/**
 * Get the resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Access;

use iMoneza\Data\ResourceAccess;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class GetResourceFromTemporaryUserToken
 * @package iMoneza\Options\Access
 */
class GetResourceFromTemporaryUserToken extends OptionsAbstract implements AccessInterface
{
    use ConfigurationTrait, AccessConfigurationTrait;

    /**
     * @var string the resource key
     */
    protected $ResourceKey = '';

    /**
     * @var string the url of this resource
     */
    protected $ResourceURL = '';

    /**
     * @var string the user token
     */
    protected $temporaryUserToken = '';

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
        $this->ResourceKey = $resourceKey;
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
     * @param string $temporaryUserToken
     * @return $this
     */
    public function setTemporaryUserToken($temporaryUserToken)
    {
        $this->temporaryUserToken = $temporaryUserToken;
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
        return "/api/TemporaryUserToken/{$this->accessKey}/{$this->temporaryUserToken}";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }

    /**
     * @return ResourceAccess
     */
    public function getDataObject()
    {
        return new ResourceAccess();
    }
}