# monolog-logdna

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require lyal/monolog-logdna
```

## Usage

``` php
$logger = new Logger('general');
$logdnaHandler = new LogDNAHandler();
$logger->debug('this is my message!');
```

## Notes

Unlike other monolog implementations of json-based log providers, this currently defaults to one request rather than retrying on failure; 
this will result in a faster request lifecycle and will prevent accidental failure ddosing of LogDNA.  

## Environment Variables

You can set two environment variables for this library:

*APP_ENV* -- the environment that the logger is running in

*LOGDNA_INGESTION_KEY* -- the ingestion key provided in your LogDNA key

*LOGDNA_HOSTNAME* -- (string) the host name of the current environment

*LOGDNA_HOST_IP* -- (string) the ip address of the currrent environment

*LOGDNA_API_URL* -- (url) the base url for your LogDNA service



## Testing

``` bash
phpunit 
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email lyal@pullrequest.com instead of using the issue tracker.

## Credits

- [Lyal Avery][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lyal/monolog-logdna.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lyal/monolog-logdna/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/lyal/monolog-logdna.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/lyal/monolog-logdna.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lyal/monolog-logdna.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lyal/monolog-logdna
[link-travis]: https://travis-ci.org/lyal/monolog-logdna
[link-scrutinizer]: https://scrutinizer-ci.com/g/lyal/monolog-logdna/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/lyal/monolog-logdna
[link-downloads]: https://packagist.org/packages/lyal/monolog-logdna
[link-author]: https://github.com/lyal
