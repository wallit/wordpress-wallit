<?php
/**
 *
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class Resource
 * @package iMoneza\Data
 */
class Resource extends DataAbstract
{
    /**
     * @var string
     */
    const ACCESS_ACTION_AUTHENTICATE = 'Authenticate';

    /**
     * @var string
     */
    const ACCESS_REASON_DENY = 'Deny';

    /**
     * @var string
     */
    const PAYWALL_DISPLAY_STYLE_MOBILE_REDIRECT = 'RedirectMobile';

    /**
     * @var string
     */
    protected $userToken;

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var string
     */
    protected $paywallDisplayStyle;

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var boolean
     */
    protected $isAnonymousUser;

    /**
     * @var Quota
     */
    protected $quota;

    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var Purchase
     */
    protected $purchase;

    /**
     * @var string
     */
    protected $accessAction;

    /**
     * @var string
     */
    protected $accessReason;

    /**
     * @var string
     */
    protected $accessActionUrl;

    /**
     * @var array keys to create classes
     */
    protected $classKeys = ['Quota', 'Purchase', 'Subscription'];

    /**
     * @return string
     */
    public function getUserToken()
    {
        return $this->userToken;
    }

    /**
     * @param string $userToken
     * @return Resource
     */
    public function setUserToken($userToken)
    {
        $this->userToken = $userToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     * @return Resource
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaywallDisplayStyle()
    {
        return $this->paywallDisplayStyle;
    }

    /**
     * @param string $paywallDisplayStyle
     * @return Resource
     */
    public function setPaywallDisplayStyle($paywallDisplayStyle)
    {
        $this->paywallDisplayStyle = $paywallDisplayStyle;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @param string $resourceName
     * @return Resource
     */
    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return Resource
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAnonymousUser()
    {
        return $this->isAnonymousUser;
    }

    /**
     * @param boolean $isAnonymousUser
     * @return Resource
     */
    public function setIsAnonymousUser($isAnonymousUser)
    {
        $this->isAnonymousUser = $isAnonymousUser;
        return $this;
    }

    /**
     * @return Quota
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param Quota $quota
     * @return Resource
     */
    public function setQuota(Quota $quota)
    {
        $this->quota = $quota;
        return $this;
    }

    /**
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     * @return Resource
     */
    public function setSubscription(Subscription $subscription)
    {
        $this->subscription = $subscription;
        return $this;
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * @param Purchase $purchase
     * @return Resource
     */
    public function setPurchase(Purchase $purchase)
    {
        $this->purchase = $purchase;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessAction()
    {
        return $this->accessAction;
    }

    /**
     * @param string $accessAction
     * @return Resource
     */
    public function setAccessAction($accessAction)
    {
        $this->accessAction = $accessAction;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessReason()
    {
        return $this->accessReason;
    }

    /**
     * @param string $accessReason
     * @return Resource
     */
    public function setAccessReason($accessReason)
    {
        $this->accessReason = $accessReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessActionUrl()
    {
        return $this->accessActionUrl;
    }

    /**
     * @param string $accessActionUrl
     * @return Resource
     */
    public function setAccessActionUrl($accessActionUrl)
    {
        $this->accessActionUrl = $accessActionUrl;
        return $this;
    }
}
