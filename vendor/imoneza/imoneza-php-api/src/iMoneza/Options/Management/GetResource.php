<?php
/**
 * Get a single Resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Data\Resource;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class GetResource
 * @package iMoneza\Options\Management
 */
class GetResource extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @var string the identifier of this resource
     */
    private $resourceKey;

    /**
     * @param string $resourceKey
     * @return GetResource
     */
    public function setResourceKey($resourceKey)
    {
        $this->resourceKey = $resourceKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}/Resource/{$this->resourceKey}";
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
        return new Resource();
    }
}