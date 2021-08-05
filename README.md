# WordPress Hook Attributes

## Installation

`composer require boxuk/wp-hook-attributes`

## Usage

Somewhere late in your WordPress bootstrap (mu-plugin like `zzz-plugin.php` works quite well) put the following:

```php
use BoxUk\WpHookAttributes\WordPressHookAttributes;

( new WordPressHookAttributes() )()
```

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

Not on PHP8 yet? You can also use annotations:

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

Currently only works with defined functions and declared classes. Which is why it needs to appear late in the bootstrap process. What would be better and hopefully will be added soon, is to grab autoload data from composer and register from there. In the mean time files and classes can be manually registered like this:

```php
use BoxUk\WpHookAttributes\WordPressHookAttributes;
use BoxUk\WpHookAttributes\HookResolver;
use BoxUk\WpHookAttributes\WordPressHookCaller;

$hookResolver = new HookResolver();
$hookResolver->registerFunctionsFile('path/to/file/with/functions/in.php');
$hookResolver->registerClass('NameOfClass');

$hookAttributesManager = new HookAttributesManager($hookResolver, new WordPressHookCaller());
$hookAttributesManager->init();
```
