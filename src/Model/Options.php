<?php
/**
 * The Options file
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Model;

/**
 * Class Options
 * @package iMonezaPRO\Model
 */
class Options
{
    /**
     * @var string for client side access control
     */
    const ACCESS_CONTROL_CLIENT = 'C';

    /**
     * @var string for server side access control
     */
    const ACCESS_CONTROL_SERVER = 'S';

    /**
     * @var bool
     */
    protected $dynamicallyCreateResources;

    /**
     * @var string
     */
    protected $managementApiKey;

    /**
     * @var string
     */
    protected $managementApiSecret;

    /**
     * @var string
     */
    protected $accessApiKey;

    /**
     * @var string
     */
    protected $accessApiSecret;

    /**
     * @var string
     */
    protected $accessControl;

    /**
     * @var array
     */
    protected $pricingGroups;

    /**
     * @var string
     */
    protected $propertyTitle;

    /**
     * @var \DateTime
     */
    protected $lastUpdated;

    /**
     * @return boolean
     */
    public function isDynamicallyCreateResources()
    {
        return $this->dynamicallyCreateResources;
    }

    /**
     * @param boolean $dynamicallyCreateResources
     * @return Options
     */
    public function setDynamicallyCreateResources($dynamicallyCreateResources)
    {
        $this->dynamicallyCreateResources = $dynamicallyCreateResources;
        return $this;
    }

    /**
     * @return string
     */
    public function getManagementApiKey()
    {
        return $this->managementApiKey;
    }

    /**
     * @param string $managementApiKey
     * @return Options
     */
    public function setManagementApiKey($managementApiKey)
    {
        $this->managementApiKey = $managementApiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getManagementApiSecret()
    {
        return $this->managementApiSecret;
    }

    /**
     * @param string $managementApiSecret
     * @return Options
     */
    public function setManagementApiSecret($managementApiSecret)
    {
        $this->managementApiSecret = $managementApiSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessApiKey()
    {
        return $this->accessApiKey;
    }

    /**
     * @param string $accessApiKey
     * @return Options
     */
    public function setAccessApiKey($accessApiKey)
    {
        $this->accessApiKey = $accessApiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessApiSecret()
    {
        return $this->accessApiSecret;
    }

    /**
     * @param string $accessApiSecret
     * @return Options
     */
    public function setAccessApiSecret($accessApiSecret)
    {
        $this->accessApiSecret = $accessApiSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessControl()
    {
        return $this->accessControl;
    }

    /**
     * @param string $accessControl
     * @return Options
     */
    public function setAccessControl($accessControl)
    {
        $this->accessControl = $accessControl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAccessControlServer()
    {
        return $this->accessControl == self::ACCESS_CONTROL_SERVER;
    }

    /**
     * @return bool
     */
    public function isAccessControlClient()
    {
        return $this->accessControl == self::ACCESS_CONTROL_CLIENT;
    }

    /**
     * @return array
     */
    public function getPricingGroups()
    {
        return $this->pricingGroups;
    }

    /**
     * @param array $pricingGroups
     * @return Options
     */
    public function setPricingGroups($pricingGroups)
    {
        $this->pricingGroups = $pricingGroups;
        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyTitle()
    {
        return $this->propertyTitle;
    }

    /**
     * @param string $propertyTitle
     * @return Options
     */
    public function setPropertyTitle($propertyTitle)
    {
        $this->propertyTitle = $propertyTitle;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @param \DateTime $lastUpdated
     * @return Options
     */
    public function setLastUpdated(\DateTime $lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    /**
     * Sets it to the current time
     * @return $this
     */
    public function setLastUpdatedNow()
    {
        $this->setLastUpdated(new \DateTime());
        return $this;
    }

    /**
     * @return bool whether this has actually been populated ever with user data or not
     */
    public function isInitialized()
    {
        return !empty($this->lastUpdated);
    }
}