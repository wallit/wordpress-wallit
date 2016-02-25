<?php
/**
 * Get the property
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class Property
 * @package iMoneza\Options\Management
 */
class Property extends OptionsAbstract implements ManagementInterface
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
}