# iMoneza PHP API Documentation

## Data Objects

The second parameter of the `Connection::request()` method requires a data object object of the interface `iMoneza\Data\DataInterface` in order
to hold the resultant data.

There are a number of getters and setters that can be used to store the data set with this object.  You can expect that the connection request method
will populate the data object for you.  You can, however, overwrite any of that data by accessing the setters yourself.

The names of the objects reflect the name of the data they will contain.  Some data objects are used directly in the `request` 
method.  Others are populated internally when the parent object needs a child object or collection of objects.  Date and time 
information is represented with the PHP `DateTime` object.

Let's look through them.

### Data Object Definitions

#### `iMoneza\Data\PricingGroup`

This represents the pricing group of a resource.

- `setPricingGroupID()`/`getPricingGroupID()`  The pricing group ID.

- `setName()`/`getName()`  The name of the pricing group.

- `setIsDefault()`/`isDefault()`  Whether this is the default pricing group.

- `setPricingModel()`/`getPricingModel()`  

- `setPrice()`/`getPrice()`  The price.

- `setExpirationPeriodUnit()`/`getExpirationPeriodUnit()`  

- `setTargetConversionRate()`/`getTargetConversionRate()`  

- `setTargetConversionPriceFloor()`/`getTargetConversionPriceFloor()`  

- `setTargetConversionHitsPerRecalculationPeriod()`/`getTargetConversionHitsPerRecalculationPeriod()`  

#### `iMoneza\Data\Property`

This represents your property.

- `setName()`/`getName()`  The name of the property.

- `setTitle()`/`getTitle()`  The title of the property.

- `setDynamicallyCreateResources()`/`isDynamicallyCreateResources()`  Whether to dynamically create resources.

- `setEnableQuota()`/`isEnableQuota()`  Whether a quota or meter is activated.

- `setEnableSubscriptions()`/`isEnableSubscriptions()`  Whether subscriptions are enabled.

- `setEnableSinglePurchases()`/`isEnableSinglePurchases()`  Whether ala-cart single purchases are enabled.

- `setFreeResourcesRequireAuthentication()`/`isFreeResourcesRequireAuthentication()`  Whether free resources require authentication.

- `setQuota()`/`getQuota()`  The quota value.

- `setQuotaPeriod()`/`getQuotaPeriod()`  The quota period value.

- `setSubscriptionGroups()`/`getSubscriptionGroups()`  An array of subscription group objects.

- `setPricingGroups()`/`getPricingGroups()`  An array of pricing group objects

- `setAlwaysRequireAuthentication()`/`isAlwaysRequireAuthentication()`  Whether to always require authentication

#### `iMoneza\Data\Purchase`

This represents a purchase.

- `setIsPurchased()`/`isPurchased()`  Whether it is purchased or not.

#### `iMoneza\Data\Quota`

This represents a quota or meter.

- `setIsEnabled()`/`isEnabled()`  Whether it is enabled.

- `setHitCount()`/`getHitCount()`  Gets hits against this quota.

- `setAllowedHits()`/`getAllowedHits()`  The allowed hits.

- `setPeriodStartDate()`/`getPeriodStartDate()`  The start of this quota period.

- `setPeriodName()`/`getPeriodName()`  The name of this period.

- `setIsMet()`/`isMet()`  Whether this quota is met or not.

#### `iMoneza\Data\Resource`

This represents a resource.

- `setName()`/`getName()`  The internal name of the resource

- `setExternalKey()`/`getExternalKey()` The external key of the resource

- `setActive()`/`isActive()`  Whether this resource is active or not

- `setURL()`/`getURL()`  The URL of the resource

- `setTitle()`/`getTitle()`  The title of the resource

- `setDescription()`/`getDescription()` The description of this resource 

- `setByline()`/`getByline()`  The author of this resource

- `setPublicationDate()`/`getPublicationDate()`  The publication date

- `setPricingGroup()`/`getPricingGroup()`  A PricingGroup data object

- `setPricingModel()`/`getPricingModel()`  The pricing model for this resource

- `setExpirationPeriodUnit()`/`getExpirationPeriodUnit()`  

- `setExpirationPeriodValue()`/`getExpirationPeriodValue()` 

- `setTargetConversionRate()`/`getTargetConversionRate()` 

- `setTargetConversionPriceFloor()`/`getTargetConversionPriceFloor()`  

- `setTargetConversionHitsPerRecalculationPeriod()`/`getTargetConversionHitsPerRecalculationPeriod()`  

- `setResourcePricingTiers()`/`getResourcePricingTiers()`  

- `setProperty()`/`getProperty()`  The property associated with this resource

#### `iMoneza\Data\ResourceAccess`

This represents the access available to a resource for the consumer.

- `setUserToken()`/`getUserToken()` The user token

- `setPropertyName()`/`getPropertyName()` The name of the property the resource belongs to

- `setPaywallDisplayStyle()`/`getPaywallDisplayStyle()` The paywall display style

- `setResourceName()`/`getResourceName()` The name of the resource

- `setUserName()`/`getUserName()` Username of the current user

- `setIsAnonymousUser()`/`isAnonymousUser()` Whether anonymous user or not

- `setQuota()`/`getQuota()` get `iMoneza\Data\Quota` for this

- `setSubscription()`/`getSubscription()` get `iMoneza\Data\Subscription` for this

- `setPurchase()`/`getPurchase()` get `iMoneza\Data\Purchase` for this

- `setAccessAction()`/`getAccessAction()` 

- `setAccessReason()`/`getAccessReason()` 

- `setAccessActionUrl()`/`getAccessActionUrl()` The URL to send the access to

- `setFirstName()`/`getFirstName()` The first name of the current user

- `setIsAdSupported()`/`isAdSupported()` 

- `setAdSupportedMessage()`/`getAdSupportedMessage()`

- `setAdSupportedMessageTitle()`/`getAdSupportedMessageTitle()`

- `setIsNoCost()`/`isNoCost()`


#### `iMoneza\Data\ResourceCollection`

This is a collection object that implements array access.  It contains elements of the object Resource.

#### `iMoneza\Data\Subscription`

Represents the Subscription.

- `setIsExpired()`/`isExpired()` Whether the subscription is expired.

- `setExpirationDate()`/`getExpirationDate()` The expiration date

- `setIsCurrent()`/`isCurrent()` Whether it's current

- `setSubscriptionGroupID()`/`getSubscriptionGroupID()` The ID

#### `iMoneza\Data\SubscriptionGroup`

Represents the Subscription Group.

- `setSubscriptionGroupID()`/`getSubscriptionGroupID()`  Set Subscription Group ID

- `setName()`/`getName()`  The internal name of the subscription group.

- `setTitle()`/`getTitle()`  The title of the group

- `setPrice()`/`getPrice()`  The price

- `setPeriod()`/`getPeriod()`  

- `setPaywallDescription()`/`getPaywallDescription()`

- `setPaywallShortDescription()`/`getPaywallShortDescription()`

#### `iMoneza\Data\ExternalSubscriberExportResponse`

Represents the External Subscription Export Request response.

- `setID()`/`getId()`  The ID of the request - NOT the callback ID.

- `setNotificationEmailAddress()`/`getNotificationEmailAddress()`  

- `setStatus()`/`getStatus()`  The status of the request.

- `setStartDate()`/`getStartDate()`  When the requested export begins.

- `setEndDate()`/`getEndDate()`  When the requested export ends.

#### `iMoneza\Data\None`

This is a special data object that is used for methods that return no data - or if you want to ignore any data that was retrieved.

#### `iMoneza\Data\CallbackResult`

This is a special data object that just accepts any data and puts it as public properties on itself - very similar to a `stdClass`.