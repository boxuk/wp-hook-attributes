# WordPress Hook Attributes

> **This library should be considered experimental and not production ready.**

## Installation

`composer require boxuk/wp-hook-attributes`

## Usage

If you are using composer for autoloading it will use the classmap 

Now you can annotate functions and methods with attributes to attach them to a hook.

```php
use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

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

> Note: It will be _really_ slow - caching hasn't been added yet. Unfortunately the built in CachedReader doesn't support function annotations.

```php
use BoxUk\WpHookAttributes\Annotations\Action;
use BoxUk\WpHookAttributes\Annotations\Filter;

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

## Registering files and classes

Currently only works with defined functions and declared classes. Composer classmap support is being looked at. For now though you can register files and classes manually if you need:

```php
use BoxUk\WpHookAttributes\WordPressHookAttributes;

(new WordPressHookAttributes())()->getHookResolver()->registerFunctionsFile('/path/to/functions.php');
(new WordPressHookAttributes())()->getHookResolver()->registerClass('ClassName');
```
