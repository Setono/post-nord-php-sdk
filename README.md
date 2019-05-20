# PostNord PHP SDK

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

A PHP SDK for the [PostNord API](https://developer.postnord.com/api).

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this library:

```bash
$ composer require setono/post-nord-php-sdk
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

## Usage
Here is an example showing you can get the [nearest service points](https://developer.postnord.com/api/docs/location#!/Find_Nearest_Service_Points/RestBusinesslocationV1ServicepointFindNearestByAddressByReturntypeGet).

**Notice** that this example uses two libraries that are not installed by default: The PSR 17 factory and the HTTP client implementation.
If you don't have any preferences, you can install these two libraries: `$ composer require kriswallsmith/buzz nyholm/psr7`.

```php
<?php
use Buzz\Client\Curl;
use Nyholm\Psr7\Factory\Psr17Factory;
use Setono\PostNord\Client\Client;

$psr17Factory = new Psr17Factory();
$httpClient = new Curl($psr17Factory);

$client = new Client($httpClient, $psr17Factory, $psr17Factory, 'INSERT API KEY');
$client->get('/rest/businesslocation/v1/servicepoint/findNearestByAddress.json', [
    'countryCode' => 'DK',
    'postalCode' => '9000',
]);
```

If you know that you will only use the [Location API](https://developer.postnord.com/api/docs/location) you could change the base URL when you instantiate the client to make the requests easier to read:

```php
<?php
use Buzz\Client\Curl;
use Nyholm\Psr7\Factory\Psr17Factory;
use Setono\PostNord\Client\Client;

$psr17Factory = new Psr17Factory();
$httpClient = new Curl($psr17Factory);

$client = new Client($httpClient, $psr17Factory, $psr17Factory, 'INSERT API KEY', 'https://api2.postnord.com/rest/businesslocation/v1/servicepoint');
$client->get('findNearestByAddress.json', [
    'countryCode' => 'DK',
    'postalCode' => '9000',
]);
```

[ico-version]: https://poser.pugx.org/setono/post-nord-php-sdk/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/post-nord-php-sdk/v/unstable
[ico-license]: https://poser.pugx.org/setono/post-nord-php-sdk/license
[ico-travis]: https://travis-ci.com/Setono/post-nord-php-sdk.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/post-nord-php-sdk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/post-nord-php-sdk
[link-travis]: https://travis-ci.com/Setono/post-nord-php-sdk
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/post-nord-php-sdk
