# Config Service

The Config Service provides a way for managing configuration data in an application.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
	- [Simple Example](#simple-example)
- [Documentation](#documentation)
    - [Create Config](#create-config)
    - [Set Data](#set-data)
    - [Load Data](#load-data)
        - [PHP Loader](#php-loader)
        - [JSON Loader](#json-loader)
    - [Get Data](#get-data)
    - [Translations](#translations)
- [Credits](#credits)
___

# Getting started

Add the latest version of the config service running this command.

```
composer require tobento/service-config
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design
- Translation support

## Simple Example

Here is a simple example of how to use the config service:

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

// create config:
$config = new Config(new Translations());

// adding a loader:
$dirs = (new Dirs())->dir('home/private/config');

$config->addLoader(new PhpLoader($dirs));

// loading data from a file:
$config->load(file: 'app.php', key: 'app');

// or set data directly:
$config->set('database', ['name' => 'db_name']);

// Get config data:
$appName = $config->get('app.name');

$dbName = $config->get('database.name');
```

# Documentation

## Create Config

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\ConfigInterface;
use Tobento\Service\Collection\Translations;

$trans = new Translations();

$config = new Config($trans);

var_dump($config instanceof ConfigInterface);
// bool(true)
```

## Set Data

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());

$config->set('database', [
    'host' => 'localhost',
    'name' => 'db_name',
]);

$config->set('sitename', 'A sitename');
```

**By dot notation**

You might set or add new data by using dot notation:

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());

$config->set('database', [
    'host' => 'localhost',
    'name' => 'db_name',
]);

// Add new data:
$config->set('database.driver', 'mysql');

// Set data (overwrites existing):
$config->set('database.name', 'db_name');
```

## Load Data

You might use loaders to load data from files.

### PHP Loader

The php loader, loads data from php files returning an array of data.

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

// create config:
$config = new Config(new Translations());

// add loader:
$dirs = (new Dirs())->dir('home/private/config');

$config->addLoader(new PhpLoader($dirs));

// loading data:
$config->load(file: 'database.php', key: 'database');
```

**database.php config file**

```php
return [
    'host' => 'localhost',
    'name' => 'db_name',
];
```

**Only loading data**

You may omit the **key:** to load data without storing.

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

// create config:
$config = new Config(new Translations());

// add loader:
$dirs = (new Dirs())->dir('home/private/config');

$config->addLoader(new PhpLoader($dirs));

// just loading data:
$data = $config->load(file: 'database.php');
```


### JSON Loader

The json loader, loads data from json files.

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\JsonLoader;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

// create config:
$config = new Config(new Translations());

// add loader:
$dirs = (new Dirs())->dir('home/private/config');

$config->addLoader(new JsonLoader($dirs));

// loading data:
$config->load(file: 'database.json', key: 'database');
```

## Get Data

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());

$config->set('sitename', 'A sitename');
$config->set('app.name', 'An app name');

$sitename = $config->get('sitename');

// using dot notation:
$appname = $config->get('app.name');
```

**Default Value**

You might set a default value to return if the data requested doesn't exist, otherwise a ConfigNotFoundException is thrown.

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Config\ConfigNotFoundException;

$config = new Config(new Translations());

$sitename = $config->get('sitename', 'Default Sitename');

// would throw ConfigNotFoundException:
$sitename = $config->get('sitename');
```

**A note on default value and data type**

You can use the default value to ensure the right data type is returned.

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());

$sites = $config->get('sites', 'Sites');

// returns the default value:
$sites = $config->get('sites', ['first', 'second']);

// returns the value set as the same data type:
$sites = $config->get('sites', 'Default Sites');
```

## Translations

You might want to load, set and get translated config data.

**Create Config**

You might configure the translations based your needs. For more info visit [Collection Service - Translations](https://github.com/tobento-ch/service-collection#translations)

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$trans = new Translations();
$trans->setLocaleFallbacks(['it' => 'en']);
$trans->setLocaleMapping(['en-Us' => 'en']);

$config = new Config($trans);
```

**Set Data**

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());

// default locale:
$config->set('sitename', 'Sitename');

// de-CH locale:
$config->set('sitename', 'Seitenname', 'de-CH');
```

**Load Data**

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

// create config:
$config = new Config(new Translations());

// add loader:
$dirs = (new Dirs())->dir('home/private/config');

$config->addLoader(new PhpLoader($dirs));

// loading default data:
$config->load(file: 'site.php', key: 'site');

// loading de locale data:
$config->load(
    file: 'de/site.php',
    key: 'site',
    locale: 'de'
);
```

**Get Data**

```php
use Tobento\Service\Config\Config;
use Tobento\Service\Collection\Translations;

$config = new Config(new Translations());
$config->set('sitename', 'Sitename');
$config->set('sitename', 'Seitenname', 'de');

var_dump($config->get('sitename'));
// string(8) "Sitename"

var_dump($config->get(key: 'sitename', locale: 'de'));
// string(10) "Seitenname"

// returns default as locale does not exist
// and no other fallback is set:
var_dump($config->get(key: 'sitename', locale: 'it'));
// string(8) "Sitename"

// returns default value set as locale does not exist:
var_dump($config->get(key: 'sitename', default: 'Site It', locale: 'it'));
// string(7) "Site It"
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)