<?php
/**
 * The Options file
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Model;
use iMoneza\Data\PricingGroup;

/**
 * Class Options
 * @package iMoneza\WordPress\Model
 */
class Options implements \JsonSerializable
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
     * @var bool
     */
    protected $indicatePremiumContent;

    /**
     * @var string the class(es) to indicate on an indicator
     */
    protected $premiumIndicatorIconClass;

    /**
     * @var string when they want to use a custom string
     */
    protected $premiumIndicatorCustomText = 'custom'; // default label

    /**
     * @var bool
     */
    protected $notifyAdblocker;

    /**
     * @var string
     */
    protected $adblockNotification = "We noticed you have your ad blocker on.  Is it possible for you to turn it off - just for this site - as a favor?  We wouldn't ask except that our website needs ads to survive.";

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $array = [
            'dynamicallyCreateResources'    =>  $this->dynamicallyCreateResources,
            'accessControl' =>  $this->accessControl,
            'propertyTitle' =>  $this->propertyTitle
        ];

        // build pricing groups
        $pricingGroupsArray = [];
        $pricingGroups = $this->pricingGroups;
        usort($pricingGroups, function($pricingGroupA, $pricingGroupB) {
            return $pricingGroupA->isDefault() ? -1 : 1;
        });
        /** @var \iMoneza\Data\PricingGroup $pricingGroup */
        foreach ($pricingGroups as $pricingGroup) {
            $pricingGroupsArray[] = ['pricingGroupID'=>$pricingGroup->getPricingGroupID(), 'name' => $pricingGroup->getName()];
        }

        $array['pricingGroups'] = $pricingGroupsArray;

        return $array;
    }

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
     * Sets the pricing groups with the default one in position 0
     * @param array $pricingGroups
     * @return Options
     */
    public function setPricingGroupsBubbleDefaultToTop(array $pricingGroups) {
        usort($pricingGroups, function(PricingGroup $pricingGroupA, PricingGroup $pricingGroupB) {
            return $pricingGroupA->isDefault() ? -1 : 1;
        });
        return $this->setPricingGroups($pricingGroups);
    }

    /**
     * Shortcut to get the default pricing group
     *
     * @return PricingGroup|null
     */
    public function getDefaultPricingGroup()
    {
        if ($this->pricingGroups[0]->isDefault()) return $this->pricingGroups[0];  // normally because of self::setPricingGroupsBubbleDefaultToTop

        foreach ($this->pricingGroups as $pricingGroup) {
            if ($pricingGroup->isDefault()) return $pricingGroup;
        }

        return null;
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

    /**
     * @return boolean
     */
    public function isIndicatePremiumContent()
    {
        return $this->indicatePremiumContent;
    }

    /**
     * @param boolean $indicatePremiumContent
     * @return Options
     */
    public function setIndicatePremiumContent($indicatePremiumContent)
    {
        $this->indicatePremiumContent = $indicatePremiumContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPremiumIndicatorIconClass()
    {
        return $this->premiumIndicatorIconClass;
    }

    /**
     * @param string $premiumIndicatorIconClass
     * @return Options
     */
    public function setPremiumIndicatorIconClass($premiumIndicatorIconClass)
    {
        $this->premiumIndicatorIconClass = $premiumIndicatorIconClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getPremiumIndicatorCustomText()
    {
        return $this->premiumIndicatorCustomText;
    }

    /**
     * @param string $premiumIndicatorCustomText
     * @return Options
     */
    public function setPremiumIndicatorCustomText($premiumIndicatorCustomText)
    {
        $this->premiumIndicatorCustomText = $premiumIndicatorCustomText;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNotifyAdblocker()
    {
        return $this->notifyAdblocker;
    }

    /**
     * @param boolean $notifyAdblocker
     * @return Options
     */
    public function setNotifyAdblocker($notifyAdblocker)
    {
        $this->notifyAdblocker = $notifyAdblocker;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdblockNotification()
    {
        return $this->adblockNotification;
    }

    /**
     * @param string $adblockNotification
     * @return Options
     */
    public function setAdblockNotification($adblockNotification)
    {
        $this->adblockNotification = $adblockNotification;
        return $this;
    }
}