<?php

use BoxUk\WpHookAttributes\WordPressHookAttributes;

/**
 * Plugin Name: WordPress Hook Attributes
 * Plugin URI: https://boxuk.com/
 * Description: A library to allow the use of PHP attributes for WordPress hooks.
 * Version: 0.0.1
 * Author: Box UK
 * Author URI: https://boxuk.com
 */

// autoloader.
if( ! class_exists( WordPressHookAttributes::class ) ){
    require __DIR__ . '/vendor/autoload.php';
}

// Composer support is experimental for now so turn off for now.
(new WordPressHookAttributes())(false);
