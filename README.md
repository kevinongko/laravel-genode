# laravel-genode

[![Laravel](https://img.shields.io/badge/laravel-5-orange.svg)](http://laravel.com)
[![Latest Stable Version](https://poser.pugx.org/kevinongko/laravel-genode/v/stable)](https://packagist.org/packages/kevinongko/laravel-genode)
[![Latest Unstable Version](https://poser.pugx.org/kevinongko/laravel-genode/v/unstable)](https://packagist.org/packages/kevinongko/laravel-genode)
[![License](https://poser.pugx.org/kevinongko/laravel-genode/license)](https://github.com/kevinongko/laravel-genode/blob/master/LICENSE)

Opinionated modular structure for Laravel


## Installation
### Composer
Install through composer by running this command:

```sh
$ composer require kevinongko/laravel-genode
```
### Service provider
Add the following code to service providers in `config/app.php`
```php
'providers' => [

  KevinOngko\LaravelGenode\LaravelGenodeServiceProvider::class,
  
],
```

Publish the package configuration by running this command:
```sh
$ php artisan vendor:publish --provider="KevinOngko\LaravelGenode\LaravelGenodeServiceProvider"
```

### Autoload Modules
Laravel Genode is using [wikimedia/composer-merge-plugin](https://github.com/wikimedia/composer-merge-plugin) to autoload modules, add this to your project's `composer.json`
```json
"extra": {
  "merge-plugin": {
    "include": [
      "modules/*/composer.json"
    ]
  }
}
```

## Usage
Create new module:
```sh
$ php artisan module:new
```

Enable modules in `config/module.php`
```php
  'active' => [
     'Module1',
     'Module2
  ],
```


## License

Laravel Genode is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
