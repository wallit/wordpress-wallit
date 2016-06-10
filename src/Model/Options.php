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
     * @var boolean a helper to make the functionality of getting default values easier
     */
    const GET_DEFAULT = true;
    
    /**
     * @var string for client side access control
     */
    const ACCESS_CONTROL_CLIENT = 'C';

    /**
     * @var string for server side access control
     */
    const ACCESS_CONTROL_SERVER = 'S';

    /**
     * @var string the default manage api
     */
    const DEFAULT_MANAGE_API_URL = "https://manageapi.imoneza.com";

    /**
     * @var string the default access api
     */
    const DEFAULT_ACCESS_API_URL = "https://accessapi.imoneza.com";

    /**
     * @var string the default cdn file
     */
    const DEFAULT_JAVASCRIPT_CDN_URL = "https://cdn.imoneza.com/paywall.min.js";

    /**
     * @var string the default ui
     */
    const DEFAULT_MANAGE_UI_URL = "https://manageui.imoneza.com";

    /**
     * @var bool
     */
    protected $dynamicallyCreateResources;

    /**
     * @var string the management api URL
     */
    protected $manageApiUrl;
    
    /**
     * @var string
     */
    protected $manageApiKey;

    /**
     * @var string
     */
    protected $manageApiSecret;

    /**
     * @var string the manage UI URL
     */
    protected $manageUiUrl;

    /**
     * @var string the access api url
     */
    protected $accessApiUrl;
    
    /**
     * @var string
     */
    protected $accessApiKey;

    /**
     * @var string
     */
    protected $accessApiSecret;

    /**
     * @var string the javascript CDN
     */
    protected $javascriptCdnUrl;

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
     * @var string
     */
    protected $premiumIndicatorCustomColor = '#444444';

    /**
     * This is used when we refresh our options and we need to communicate this via ajax 
     * 
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
        usort($pricingGroups, function(PricingGroup $pricingGroupA, PricingGroup $pricingGroupB) {
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
     * @param bool $getDefault
     * @return string
     */
    public function getManageApiUrl($getDefault = false)
    {
        $url = $this->manageApiUrl;
        if ($getDefault && !$url) {
            $url = self::DEFAULT_MANAGE_API_URL;
        }
        return $url;
    }

    /**
     * @param string $manageApiUrl
     * @return Options
     */
    public function setManageApiUrl($manageApiUrl)
    {
        $this->manageApiUrl = $manageApiUrl;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getManageApiKey()
    {
        return $this->manageApiKey;
    }

    /**
     * @param string $manageApiKey
     * @return Options
     */
    public function setManageApiKey($manageApiKey)
    {
        $this->manageApiKey = $manageApiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getManageApiSecret()
    {
        return $this->manageApiSecret;
    }

    /**
     * @param string $manageApiSecret
     * @return Options
     */
    public function setManageApiSecret($manageApiSecret)
    {
        $this->manageApiSecret = $manageApiSecret;
        return $this;
    }

    /**
     * @param bool $getDefault
     * @return string
     */
    public function getManageUiUrl($getDefault = false)
    {
        $url = $this->manageUiUrl;
        if ($getDefault && !$url) {
            $url = self::DEFAULT_MANAGE_UI_URL;
        }
        return $url;
    }

    /**
     * @param string $manageUiUrl
     * @return Options
     */
    public function setManageUiUrl($manageUiUrl)
    {
        $this->manageUiUrl = $manageUiUrl;
        return $this;
    }
    
    /**
     * @param bool $getDefault
     * @return string
     */
    public function getAccessApiUrl($getDefault = false)
    {
        $url = $this->accessApiUrl;
        if ($getDefault && !$url) {
            $url = self::DEFAULT_ACCESS_API_URL;
        }
        return $url;
    }

    /**
     * @param string $accessApiUrl
     * @return Options
     */
    public function setAccessApiUrl($accessApiUrl)
    {
        $this->accessApiUrl = $accessApiUrl;
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
     * @param bool $getDefault
     * @return string
     */
    public function getJavascriptCdnUrl($getDefault = false)
    {
        $url = $this->javascriptCdnUrl;
        if ($getDefault && !$url) {
            $url = self::DEFAULT_ACCESS_API_URL;
        }
        return $url;
    }

    /**
     * @param string $javascriptCdnUrl
     * @return Options
     */
    public function setJavascriptCdnUrl($javascriptCdnUrl)
    {
        $this->javascriptCdnUrl = $javascriptCdnUrl;
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
     * @return bool whether the plugin has been initialized or not
     */
    public function isInitialized()
    {
        return !empty($this->manageApiKey);
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
     * @return string
     */
    public function getPremiumIndicatorCustomColor()
    {
        return $this->premiumIndicatorCustomColor;
    }

    /**
     * @param string $premiumIndicatorCustomColor
     * @return Options
     */
    public function setPremiumIndicatorCustomColor($premiumIndicatorCustomColor)
    {
        $this->premiumIndicatorCustomColor = $premiumIndicatorCustomColor;
        return $this;
    }
}