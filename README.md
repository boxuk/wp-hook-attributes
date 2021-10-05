# WordPress Hook Attributes

[![Build Status](https://app.travis-ci.com/boxuk/wp-hook-attributes.svg?token=3rRfYiN6sMupp1z6RpzN&branch=main)](https://app.travis-ci.com/boxuk/wp-hook-attributes)

> **This library should be considered experimental and not production ready.**

## Installation

`composer require boxuk/wp-hook-attributes`

> Until this is on packagist or similar, you will need to add this repository to your `composer.json` repositories section, see below for an example:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/boxuk/wp-hook-attributes"
    }
  ]
}
```

### Enable caching (optional, recommended for production)

Basic array based caching is enabled as standard but in production you may wish to bring in a more optimal adapter. Below is an example using memcache, but any [PSR-6](https://www.php-fig.org/psr/psr-6/) adapter is supported.

`composer require cache/memcache-adapter`

```php
use Doctrine\Common\Annotations\Reader;
use BoxUk\WpHookAttributes\PsrCachedAnnotationReader;
use Cache\Adapter\Memcache\MemcacheCachePool;
use Psr\Cache\CacheItemPoolInterface;

if ( wp_get_environment_type() === 'production' ) {
	add_filter(
		'wp_hook_attributes_cache_adapter',
		function ( CacheItemPoolInterface $cache_adapter ): CacheItemPoolInterface {
			global $wp_object_cache;
			if ( $wp_object_cache->get_mc( 'default' ) instanceof \Memcache ) {
				$client = $wp_object_cache->get_mc( 'default' );

				return new MemcacheCachePool( $client );
			}

			return $cache_adapter;
		}
	);
}
```

## Usage

Now you can annotate functions and methods with attributes to attach them to a hook.

```php
use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Attributes\Filter;

// Example of using an action hook
#[Action('init')]
function basic_action(): string {
	return 'something...';
}

// Example of using a filter hook
#[Filter('the_content')]
function basic_filter(): string {
	return 'something...';
}

// You can also attach a priority and args
#[Action('init', priority: 20, args: 4)]
function advanced_action( string $arg1, int $arg2, bool $arg3, array $arg4 ): string
	return 'something...';
}
```

Not on PHP8 yet? You can use annotations instead

```php
use BoxUk\WpHookAttributes\Hook\Annotations\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Filter;

// Example of using an action hook
/**
 * @Action("init")
 */
function basic_action(): string {
	return 'something...';
}

// Example of using a filter hook
/**
 * @Filter("the_content") 
 */
function basic_filter(): string {
	return 'something...';
}

// You can also attach a priority and args
/**
 * @Action("init", priority="20", args="4")
 */
function advanced_action( string $arg1, int $arg2, bool $arg3, array $arg4 ): string
	return 'something...';
}
```

> Note: Anything lower than PHP 7.4 is not supported.

## Registering a namespace or prefix (highly recommended)

You likely want to register a namespace or prefix to ensure it only looks for attributes/annotations for your code. You can do so via the following hook:

**If you're using annotations and don't do this it will likely be extremely slow**

```php
// Namespace
add_filter( 'wp_hook_attributes_registered_namespaces', function() {
	return [
		'BoxUk\Mu\Plugins',
	];
});

// Prefix
add_filter( 'wp_hook_attributes_registered_prefixes', function() {
	return [
		'boxuk_',
	];
});
```

> It does a `stripos()` comparison, so you can just put the first part of the namespace/prefix.

## Registering files and classes

Currently only works with defined functions and declared classes that are registered before the `init` hook. To get around this you can register function files or classes manually using the following hooks. This will need to be done prior to `init` though, or the resolver will need to be called manually (details below).

```php
add_filter( 'wp_hook_attributes_registered_function_files', function( array $registered_files) {
	return array_merge(
		$registered_files,
			[
				'path/to/my/file/with/functions.php'
			]
	);
});

add_filter( 'wp_hook_attributes_registered_classes', function( array $registered_classes) {
	return array_merge(
		$registered_classes,
			[
				RegistrationService::class,
			]
	);
});
```

## Ignoring existing annotation names

Sometimes you may get errors when using annotations that an existing annotation hasn't been imported. This is because sometimes you find non-standard annotations or docblock parameters that we need to ignore. 

Some common WordPress and related libraries are ignored by default, but it won't cover everything.

You can ignore any custom annotations you need to with the following hook:

```php
add_filter( 
	'wp_hook_attributes_annotation_ignores',
	function( array $existing_ignores ): array {
		$existing_ignores[] = 'my-custom-annotation';
		return $existing_ignores;
	}
);
```

## Limitations

### Attributes on hooks prior to `init` require a bit more work

If you wish to use hooks prior to the `init` hook, for example `muplugins_loaded` you will not be able to use attributes for these without a bit more effort. As they would have already been called by the point the hook resolver is called, you will need to call the hook resolver yourself manually. For example, let's say you have a hook on `muplugins_loaded` which is a pre-init hook.

```php
/**
 * @ActionAnnotation("muplugins_loaded")
 */
#[Action('muplugins_loaded')]
function muplugins_loaded_action(): void
{
    echo 'on muplugins_loaded action';
}
```

The `muplugins_loaded` hook would have already been called by the time our `init` hook is called which calls the hook resolver. So in these scenarios, you'll need to call the hook resolver manually, e.g.

```php
/**
 * @ActionAnnotation("muplugins_loaded")
 */
#[Action('muplugins_loaded')]
function muplugins_loaded_action(): void
{
    echo 'on muplugins_loaded action';
}

// Some place earlier than `muplugins_loaded`, maybe a `000-my-project.php` or something within `mu-plugins`.
( new WordPressHookAttributes() )();
```

> See the next limitation as to why we don't load the resolver this early by default.

The main hooks that this applies to is (but not limited to):

```
muplugins_loaded
registered_taxonomy
registered_post_type
plugins_loaded
sanitize_comment_cookies
setup_theme
unload_textdomain
load_textdomain
after_setup_theme
auth_cookie_malformed
auth_cookie_valid
set_current_user
```

> Source: http://rachievee.com/the-wordpress-hooks-firing-sequence/

### Functions/methods must be registered before the `init` hook

Attributes should work for any function/method registered before the `init` hook is called. Any function/method that is registered as part of an `mu-plugin` or a `theme` should work as the hooks to load these are called prior to `init`.

What won't work is any function/method that is registered after the `init` hook, for example the following won't work because `wp_loadded` is called after `init` and thus the functions within `my-functions.php` won't be registered in time:

```php
// This will not work.
add_action( 'wp_loaded', function() {
    require_once 'my-functions.php';
});
```

You can register files manually, but again, this must be done before `init`, so to fix the above you can do:

```php
add_action( 'muplugins-loaded', function() {
    add_filter( 'wp_hook_attributes_registered_function_files', function() {
        return [
            'my-functions.php';
        ];
    });
});
```

Similarly, simply requiring it will work also:

```php
add_action( 'muplugins-loaded', function() {
    require_once 'my-functions.php';
});
```

### Non-static methods are not supported

If you have a method which relies on an instance of the current object, for examples:

```php
class Example {
    private $foo = 'world';
    
    public function hello(): string {
        return 'Hello ' . $this->foo;
    }
}
```

You are able to set up a callback using an instance of `Example`, e.g. 

```php
$example = new Example();
$callback = [ $example, 'hello' ];
```

However, this isn't supported with this library because it cannot make an assumption on how it instantiates the class. Therefore, only static methods will work. It also requires methods are marked as static even if they are implicitly static. This is good practice anyway as using a method as static if not explicitly declared will raise a PHP Deprecated on PHP 7.4 and a Fatal Error on PHP 8.
