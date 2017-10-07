Laravel-Shortcodes
====================

Laravel Shortcodes Package

# Laravel compatibility

 Laravel  | Shortcodes
:---------|:----------
 5.5.x    | 1.2.x
 5.4.x    | 1.1.x
 5.3.x    | 1.1.x
 5.2.x    | 1.1.x
 5.1.x    | 1.0.x
 5.0.x    | 1.0.x

# Installation

Require this package in your `composer.json` and update composer.

```
"gornymedia/laravel-shortcodes": "^1.2",
```

Add the service provider to `config/app.php`

```php
Gornymedia\Shortcodes\ShortcodesServiceProvider::class,
```

Add the class alias to `config/app.php`

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
Include partial files in shortcode

```php

Shortcode::add('widget', function($atts, $content, $name) 
{
 $a = Shortcode::atts(array(
  'name' => $name,
  'foo' => 'something'
  ), $atts);
  
 $file = 'partials/' . $a['name'] ; // ex: resource/views/partials/ $atts['name'] .blade.php
 
 if ( view()->exists($file) ) {
  return view($file, $a);
 }
});

Usage : [widget name="maps"]

```

Compile shortcodes inside shortcode content

```php

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

# License

This package is open-source software licensed under MIT License.

This package uses WordPress shortcodes methods. The license under which the WordPress software is released is the GPLv2 (or later).
