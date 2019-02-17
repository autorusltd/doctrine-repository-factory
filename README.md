# Doctrine Repository Factory with PHP-DI injection support

[![Latest Stable Version](https://poser.pugx.org/arus/doctrine-repository-factory/v/stable)](https://packagist.org/packages/arus/doctrine-repository-factory)
[![Total Downloads](https://poser.pugx.org/arus/doctrine-repository-factory/downloads)](https://packagist.org/packages/arus/doctrine-repository-factory)
[![License](https://poser.pugx.org/arus/doctrine-repository-factory/license)](https://packagist.org/packages/arus/doctrine-repository-factory)

## Installation (via composer)

```bash
composer require arus/doctrine-repository-factory
```

## How to use?

```php
use Arus\Doctrine\RepositoryFactory\InjectableRepositoryFactory;

/** @var $config \Doctrine\ORM\Configuration */ 
/** @var $container \DI\Container */ 

$config->setRepositoryFactory(new InjectableRepositoryFactory($container));
```

## Test run

```bash
composer test
```

## Useful links

* http://php-di.org/
* https://www.doctrine-project.org/
