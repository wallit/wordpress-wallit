# iMoneza PHP API Documentation

## Connection Object

The connection object is responsible for accepting your credentials, sending a request, retrieving a response and emitting
any errors.  To use the connection object, you must have access to both API's (access and management).  You will also need
a PSR-3 compatible logger (Monolog is suggested), and Curl (There is an interface available if you'd like to create
your own connection toolset void of curl).

### Constructing the Connection Object

The connection can be created like this:

    $request = new \iMoneza\Request\Curl();
    $logger = new \Monolog\Logger('iMoneza');
    $connection = new \iMoneza\Connection($managementApiKey, $managementSecret, $accessApiKey, $accessSecret, $request, $logger);

**$request** Use a request object to handle the transport.  The `iMoneza\Request\RequestInterface` is available if you'd
like to create your own request object.  If you have curl installed, it's best to just use this object.

**$logger** This is a PSR-3 compatible logger.  Monolog is included by default and suggested.  Remember to include your own
output handlers and set your reporting level.  _Debug_ issues the most data, _info_ has general workflow notices.  When 
an exception is issued, an _error_ is recorded as well.

**$managementApiKey** and **$managementSecret** are your management API key and secret respectively.

**$accessApiKey** and **$accessSecret** are your access API key and secret respectively.

### Sending a request

In order to send a request, you can call the `request` method of the object.  This method requires the `iMoneza\Options\OptionsInterface`
object as the first parameter, and a `iMoneza\Data\DataInterface` object as the second parameter.  The first object
contains all of the options for the request, the second contains an object that will be populated with the response.

For example, note this code:

    $options = new \iMoneza\Options\Management\GetResource();
    $options->setResourceKey('abc123');
    $data = new iMoneza\Data\Resource();
    $result = $this->connection->request($options, $data);
    
The `$result` variable will now contain the populated `$data` object.  Please note, because PHP objects are also passed
by reference, the `$data` object is identical to the `$result` object - either can be used in the subsequent code.

### Exceptions

The `request()` method will throw `iMoneza\Exception\iMoneza` exceptions when there is a problem.  Detailed below are the exceptions
and what they mean.

- `iMoneza\Exception\AccessDenied` is equivalent to an HTTP 403 error.  The request is not authorized.  Check to make sure 
that you are sending an API Key and Secret.

- `iMoneza\Exception\AuthenticationFailure` means that the api key or the secret is invalid.

- `iMoneza\Exception\DecodingError` is issued if the JSON response from iMoneza is invalid.  This should _never_ happen hopefully.

- `iMoneza\Exception\iMoneza` is the base exception.  If you wish to capture any iMoneza error, you could catch this exception.  
All other exceptions extend it.

- `iMoneza\Exception\NotFound` means that the resource you're requesting is not found.  This could mean it was moved, it changed 
somehow or that it never existed.  Commonly this is issued when a resource key is invalid.

- `iMoneza\Exception\TransferError` means that the transfer between iMoneza and you failed in some capacity that was not
already captured by a previous named exception above.

You might try an example like this:

    try {
        $result = $this->connection->request($options, $data);
    } 
    catch (\iMoneza\Exception\NotFound $e) {
        die('Uh oh - not found!');
    }
    catch (\iMoneza\Exception\iMoneza $i) {
        die('Something happened with our iMoneza connection - please check the logs.');
    }
    

