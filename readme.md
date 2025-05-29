Laravel-Shortcodes
====================

Laravel Shortcodes Package

# Laravel compatibility

 Laravel  | Shortcodes
:---------|:----------
 12.x     | 1.5
 11.x     | 1.5
 10.x     | 1.4
 9.x      | 1.4
 8.x      | 1.4
 7.x      | 1.4
 6.x      | 1.4
 5.8.x    | 1.3
 5.5.x    | 1.2
 5.4.x    | 1.1
 5.3.x    | 1.1
 5.2.x    | 1.1
 5.1.x    | 1.0
 5.0.x    | 1.0

# Installation

Require this package with Composer

```shell
composer require gornymedia/laravel-shortcodes 1.5
```

Add the service provider to `bootstrap/providers.php`

```php
Gornymedia\Shortcodes\ShortcodesServiceProvider::class,
```

To use the facades, add this within the register method of `app/Providers/AppServiceProvider`

```php
$loader = \Illuminate\Foundation\AliasLoader::getInstance();
$loader->alias('Shortcode', \Gornymedia\Shortcodes\Facades\Shortcode::class);
```

Copy the package config to your local config with the publish command

```shell
php artisan vendor:publish --provider="Gornymedia\Shortcodes\ShortcodesServiceProvider"
```

# Usage

## Creating shortcodes

Inside an `app/Providers/AppServiceProvider` boot function you can create the shortcodes with attributes.

```php
use Gornymedia\Shortcodes\Facades\Shortcode;

Shortcode::add('example', function($atts, $content, $name)
{
  $a = Shortcode::atts([
    'name' => $name,
    'foo' => 'something',
    ], $atts);
    
  return "foo = {$a['foo']}";
});

Usage : [example foo="something else"]
```

Include partial files in shortcode

```php
use Gornymedia\Shortcodes\Facades\Shortcode;

Shortcode::add('widget', function($atts, $content, $name) 
{
  $a = Shortcode::atts([
    'name' => $name,
    'foo' => 'something'
  ], $atts);
  
  $file = 'partials/' . $a['name'] ; // ex: resource/views/partials/ $atts['name'] .blade.php
 
  if (view()->exists($file)) {
    return view($file, $a);
  }
});

Usage : [widget name="maps"]
```

Compile shortcodes inside shortcode content

```php
use Gornymedia\Shortcodes\Facades\Shortcode;

Shortcode::add('strong', function($atts, $content, $name) {
 $content = Shortcode::compile($content);
 
 return "<strong>$content</strong>";
});

Usage: [strong][example][/strong]

```

## View rendering

### compileShortcodes()

To render the view and compile the Shortcodes:

```php
return view('view')->compileShortcodes();
```

### stripShortcodes()

To render the view and remove the Shortcodes

```php
return view('view')->stripShortcodes();
```

In the `config/gornymedia-laravel-shortcodes.php` file you can set the default mode (`null`, `compile` or `strip`).

The following example will compile shortcodes without using the `compileShortcodes()` method

```php
return [
    'mode' => 'compile'
];
```

# License

This package is open-source software licensed under MIT License.

This package uses WordPress shortcodes methods. The license under which the WordPress software is released is the GPLv2 (or later).

# Support

Support me with any amount and help me develop.

[![](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S4RCAGKGXMK4U&source=url)
