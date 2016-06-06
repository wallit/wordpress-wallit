# iMoneza PHP API Documentation

## Options Objects

The first parameter of the `Connection::request()` method requires an options object of the interface `iMoneza\Options\OptionsInterface` in order
to determine what the job of this connection will be.

There are a number of public methods that are used in order to facilitate this connection.  You should be mainly concerned
with any setters that are exposed, however.  This is how you will configure this particular object - if it needs to be configured, that is.

The names of the objects tend to verbosely reflect the nature of their request.

The options are spit between the Access and the Management APIs.  Let's look through them.

### Options Object Definitions

#### `iMoneza\Options\Access\GetResourceFromResourceKey`

This is used to retrieve resources from the external key (or resource key).  The following setters may be used:

- `setResourceKey()`  Use this to set the resource key for your resource you're attempting to retrieve.

- `setResourceURL()`  This is used to set the URL of the resource.

- `setUserToken()`  This is optional for the first request - but it should be used after the first request with all 
future requests.  This is how a user is identified.

- `setIP()`  This is optional but is recommended for logging and security.  Use `iMoneza\Helper\getCurrentIP()` as a useful way
to gather this information quickly.

- `setAdBlockerStatus()` This is optional, but should use one of the `AD_BLOCKER_STATUS_*` constants to indicate the result.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\Resource` - think of this as a helper to 
quickly pass in the corresponding data object for the connection `request()` method to populate.


#### `iMoneza\Options\Access\GetResourceFromTemporaryUserToken`

This is used to retrieve resources from the external key and user temp token.  The following setters may be used:

- `setResourceKey()`  Use this to set the resource key for your resource you're attempting to retrieve.

- `setResourceURL()`  This is used to set the URL of the resource.

- `setTemporaryUserToken()`  Set the temporary user token.

- `setIP()`  This is optional but is recommended for logging and security.  Use `iMoneza\Helper\getCurrentIP()` as a useful way
to gather this information quickly.

- `setAdBlockerStatus()` This is optional, but should use one of the `AD_BLOCKER_STATUS_*` constants to indicate the result.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\Resource`.


#### `iMoneza\Options\Management\GetProperty`

This is used to retrieve the current property details.  This uses your API key to determine the property.  There are no
setters that you'll need to access.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\Property`.

#### `iMoneza\Options\Management\GetResource`

This is used to retrieve a resource from the current property.

- `setResourceKey()` Use this to set the resource key (or external ID) of the resource belonging to your property that you'd like to retrieve.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\Resource`.

#### `iMoneza\Options\Management\GetResourcesFromProperty`

This is used to retrieve all the resources from the current property.  There are no setters that you'll need to access.

- `getDataObject()` This returns a new instance of the data collection object of `iMoneza\Data\ResourceCollection`.

#### `iMoneza\Options\Management\SaveResource`

This is used to save a new resource or update an existing one.  The following setters are available:

- `setExternalKey()` Use this to set the resource key (or external ID) of the resource.

- `setName()` Use this to set the the internal name of the resource.

- `setTitle()` Use this to set the title of the resource.

- `setPricingGroupId()` Use this to pass in the pricing group ID for this resource.

- `setActive()` Set a boolean whether this is active or not.

- `setURL()` Set the resource URL.

- `setByline()` The by-line of this element.

- `setDescription()` Set the description.

- `setPublicationDate()` Sets the publication date of this resource.

- `setPricingGroup()` The pricing group for this resource.

- `setPricingModel()` The pricing model for this resource.

- `setPrice()` The price of this content.

- `setExpirationPeriodUnit()` What unit expirations should be measured in.

- `setExpirationPeriodValue()` What the value for expiration should be.

- `setTargetConversionRate()`

- `setTargetConversionPriceFloor()`

- `setTargetConversionHitsPerRecalculationPeriod()`

- `setPaywallDescription()` Set the paywall description for this resource.

- `setPaywallShortDescription()` Set the paywall short description for this resource.

- `getDataObject()` This returns a new instance of a class that basically accepts no data.

#### `iMoneza\Options\Management\ExternalSubscriberExport`

This is used to initialize an external subscriber export for the current property.

- `setStartDate()` Use this to set the start date of the external subscribers export - use a `DateTime` object.

- `setEndDate()` Use this to set the end date of the external subscribers export - use a `DateTime` object.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\ExternalSubscriberExportResponse`.

#### `iMoneza\Options\Management\CallbackResult`

This retrieves the results from a callback indicating that one of the queued tasks have been completed.

- `setCallbackToken()`  Pass in the callback token.

- `getDataObject()` This returns a new instance of the data object of `iMoneza\Data\CallbackResult`.
