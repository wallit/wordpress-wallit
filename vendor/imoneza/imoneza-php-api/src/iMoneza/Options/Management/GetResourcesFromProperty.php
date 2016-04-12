<?php
/**
 * Get the property's resources
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Data\Property;
use iMoneza\Data\ResourceCollection;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class GetResourcesFromProperty
 * @package iMoneza\Options\Management
 */
class GetResourcesFromProperty extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}/Resource";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }

    /**
     * @return ResourceCollection
     */
    public function getDataObject()
    {
        return new ResourceCollection();
    }
}