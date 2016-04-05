# iMoneza PHP API

[![Build Status](https://travis-ci.org/iMoneza/imoneza-php-api.svg?branch=master)](https://travis-ci.org/iMoneza/imoneza-php-api)
[![Coverage Status](https://coveralls.io/repos/github/iMoneza/imoneza-php-api/badge.svg?branch=master)](https://coveralls.io/github/iMoneza/imoneza-php-api?branch=master)

A PHP library to integrate iMoneza into your application.

Using your <http://imoneza.com> account, you can interact with all parts of the API using this library.  This library
requires PHP 5.4+ and curl to be installed.

## Installation

This library is available on packagist and can be installed using composer.

```bash
$ composer require imoneza/imoneza-php-api
```

## Basic Usage

There are a number of ways to interact with the API.  But, the way of performing each task is relatively the same.  For 
this example, let's save a resource to iMoneza.

```PHP
$options = new \iMoneza\Options\Management\SaveResource();
$options->setExternalKey('announcement-wisconsin-crowned-king-of-cheese')
    ->setName('Wisconsin Crowned King of Cheese')
    ->setTitle('Wisconsin Crowned King of Cheese')
    ->setPricingGroupId('aaa111bb-22cc-425f-425f-aaa111bb452d');

$logger = new \Monolog\Logger('iMoneza');
$connection = new \iMoneza\Connection($apiKey, $secretKey, $accessApiKey, $accessSecretKey, new \iMoneza\Request\Curl(), $logger);
$connection->request($options, new \iMoneza\Data\None());
```

## Documentation

[Introduction](docs/01-intro.md)  
[The Connection Object](docs/02-connection.md)  
[Options Objects](docs/03-options.md)  
[Data Objects](docs/04-data.md)  
[Examples](docs/examples)

## Todo

Please note that there are a few "todo" issues out there.  These include external subscriber support and callback results.
You can view [the issues](https://github.com/iMoneza/imoneza-php-api/issues) to see what features are still slated to
be completed.
  
## About

### Requirements

 - PHP 5.4+
 - Curl
 - iMoneza publisher account
 
### Bugs, Feature Requests and Testing

Bugs and feature request are tracked on [GitHub](https://github.com/iMoneza/imoneza-php-api/issues).  Testing is managed
by [Travis CI](http://travis-ci.org) and coverage provided by Coveralls.

### Author

[iMoneza, LLC](https://imoneza.com)

#### Contributors

[Aaron Saray](https://github.com/aaronsaray)

### License

This library is licensed under the LGPLv3 License - see the [LICENSE](LICENSE) file for details

