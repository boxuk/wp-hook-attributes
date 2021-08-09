<?php

declare(strict_types=1);

use BoxUk\WpHookAttributes\HookAttributesManager;
use BoxUk\WpHookAttributes\WordPressHookAttributes;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Plugin Name: WordPress Hook Attributes
 * Plugin URI: https://boxuk.com/
 * Description: A library to allow the use of PHP attributes for WordPress hooks.
 * Version: 0.0.1
 * Author: Box UK
 * Author URI: https://boxuk.com
 */

// autoloader.
if (! class_exists(HookAttributesManager::class)) {
    require __DIR__ . '/vendor/autoload.php';
}

$annotationIgnores = apply_filters('wp_hook_attributes_annotation_ignores', [
    // WordPress.
    'type',
    'blessed',
    // WP CLI.
    'when',
    'When',
    'then',
    'Then',
    'given',
    'Given',
    // Timber
    'jarednova',
]);

foreach( $annotationIgnores as $annotationIgnore) {
    AnnotationReader::addGlobalIgnoredName($annotationIgnore);
}

(new WordPressHookAttributes())();
