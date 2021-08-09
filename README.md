# WordPress Hook Attributes

[![Build Status](https://app.travis-ci.com/boxuk/wp-hook-attributes.svg?token=3rRfYiN6sMupp1z6RpzN&branch=main)](https://app.travis-ci.com/boxuk/wp-hook-attributes)

> **This library should be considered experimental and not production ready.**

## Installation

`composer require boxuk/wp-hook-attributes`

### Enable caching

Basic array based caching is enabled as standard but you may wish to bring in a more optimal adapter. Below is an example using memacache, but any PSR-6 adapter is supported.

`composer require cache/memcache-adapter`

```php
use Doctrine\Common\Annotations\Reader;
use BoxUk\WpHookAttributes\PsrCachedAnnotationReader;
use Cache\Adapter\Memcache\MemcacheCachePool;
use Psr\Cache\CacheItemPoolInterface;

add_filter(
	'wp_hook_attributes_cache_adapter',
	function( CacheItemPoolInterface $cache_adapter ): CacheItemPoolInterface {
		global $wp_object_cache;
		if ( $wp_object_cache->get_mc( 'default' ) instanceof \Memcache ) {
			$client = $wp_object_cache->get_mc( 'default' );
			return new MemcacheCachePool( $client );
		}
		
		return $cache_adapter;
	}
);
```

> Unfortunately this is one of the few filters you'll need to use the function rather than an annotation for.

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

Not on PHP8 yet? You can also use annotations (**not recommended**):

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

## Registering a namespace

You likely want to register a namespace to ensure it only looks for attributes/annotations for your code. You can do so via the following hook:

```php
add_filter( 'wp_hook_attributes_registered_namespaces', function() {
	return [
		'BoxUk\Mu\Plugins',
	];
});
```

> It does a `stripos()` comparison, so you can just put the first part of the namespace.

## Registering files and classes

Currently only works with defined functions and declared classes. For now though you can register files and classes manually if you need:

```php
add_filter( 'wp_hook_attributes_registered_function_files', function() {
	return [
		'path/to/my/file/with/functions.php',
	];
});

add_filter( 'wp_hook_attributes_registered_classes', function() {
	return [
		'Fqcn\Of\My\Class',
	];
});
```

## Ignoring existing annotation names

Sometimes you may get errors when using annotations that an existing annotation hasn't been imported. This is because sometimes you find not standard annotations or docblock parameters that we need to ignore. 

Some common WordPress and related libraries are ignored by default but it won't cover everything.

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
