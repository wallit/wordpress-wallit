<?php
/**
 * Callback Result of External Subscriber
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class CallbackResult
 * @package iMoneza\Options\Management
 */
class CallbackResult extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @var string the callback token
     */
    private $callbackToken;

    /**
     * @param string $callbackToken
     * @return CallbackResult
     */
    public function setCallbackToken($callbackToken)
    {
        $this->callbackToken = $callbackToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}/CallbackResult/{$this->callbackToken}";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }

    /**
     * @return \iMoneza\Data\Resource
     */
    public function getDataObject()
    {
        return new \iMoneza\Data\CallbackResult();
    }
}