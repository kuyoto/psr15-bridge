# PSR-15 Decorator for Callable Middleware (double pass)

[![Build Status](https://travis-ci.org/kuyoto/psr15-bridge.svg?b=master)](https://travis-ci.org/kuyoto/psr15-bridge)
[![Latest Stable Version](https://poser.pugx.org/kuyoto/psr15-bridge/v/stable?format=flat)](https://packagist.org/packages/kuyoto/psr15-bridge)
[![License](https://poser.pugx.org/kuyoto/psr15-bridge/license?format=flat)](https://packagist.org/packages/kuyoto/psr15-bridge)

This package provides a [PSR-15](http://www.php-fig.org/psr/psr-15/) middleware that decorates a callable (double pass) middleware.

## Installation

The recommnended way to install this library is through [composer](https://getcomposer.org):

```bash
composer require kuyoto/psr15-bridge
```

## Usage

Decorates an existing callable (double pass) [PSR-7](http://www.php-fig.org/psr/psr-7/) middlewares to a [PSR-15](http://www.php-fig.org/psr/psr-15/) middleware:

```php
use Kuyoto\Psr15\Bridge\DoublePassMiddlewareDecorator;

$decorator = new DoublePassMiddlewareDecorator($callableMiddleware, $response);
```

You can add the resulting middleware objects in a [PSR-15](http://www.php-fig.org/psr/psr-15/) stack.

## Testing

```bash
composer test
```

## License

The package is an open-sourced software licensed under the [MIT License](LICENSE).
