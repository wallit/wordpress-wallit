<?php
/**
 * Get the property
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Data\Property;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class GetProperty
 * @package iMoneza\Options\Management
 */
class GetProperty extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }

    /**
     * @return Property
     */
    public function getDataObject()
    {
        return new Property();
    }
}