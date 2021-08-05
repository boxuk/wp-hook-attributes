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

// Will only attempt to use composer classmap if class map authoritative is in use.
$composerClassLoaders = array_filter(spl_autoload_functions(), fn($entry) => $entry[0] instanceof \Composer\Autoload\ClassLoader);
/** @var \Composer\Autoload\ClassLoader $composerClassLoader */
$composerClassLoader = $composerClassLoaders[0][0] ?? null;
$hasOptimisedAutoloader = $composerClassLoader instanceof \Composer\Autoload\ClassLoader ? $composerClassLoader->isClassMapAuthoritative() : false;

$useComposerClassmap = $hasOptimisedAutoloader;
(new WordPressHookAttributes())($useComposerClassmap);
