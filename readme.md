Laravel-Shortcodes
====================

Laravel Shortcodes Package

# Laravel compatibility

 Laravel  | Shortcodes
:---------|:----------
 5.0.x    | 1.0.x
 5.1.x    | 1.0.x
 5.2.x    | 1.1.x
 5.3.x    | 1.1.x
 5.4.x    | 1.1.x

# Installation

Require this package in your `composer.json` and update composer.

```
"gornymedia/laravel-shortcodes": "1.*"
```

Add the service provider to `app/config/app.php`

```
'Gornymedia\Shortcodes\ShortcodesServiceProvider',
```

Add the class alias to `app/config/app.php`

```php
'Shortcode' => 'Gornymedia\Shortcodes\Facades\Shortcode',
```

# Demo

```php
[example foo="something"]
```

# Usage

## Creating shortcodes

Inside a service provider you can create the shortcodes with attributes.

```php
Shortcode::add('example', function($atts, $content, $name)
{
  $a = Shortcode::atts(array(
    'foo' => 'something',
    'bar' => 'something else',
    ), 
    $atts
  );
    return "foo = {$a['foo']}";
});
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

# License

This package is open-source software licensed under MIT License.

This package uses WordPress shortcodes methods. The license under which the WordPress software is released is the GPLv2 (or later).
