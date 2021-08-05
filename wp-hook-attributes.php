<?php

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
if( ! class_exists( WordPressHookAttributes::class ) ){
    require __DIR__ . '/vendor/autoload.php';
}

AnnotationReader::addGlobalIgnoredName('dataprovider'); // Needed to stop phpunit errors (case sensitivity issue).
AnnotationReader::addGlobalIgnoredName('type'); // WordPress uses @type in some places.
AnnotationReader::addGlobalIgnoredName('when'); // WordPress CLI uses @when in some places.
AnnotationReader::addGlobalIgnoredName('When'); // WordPress CLI uses @When in some places.
AnnotationReader::addGlobalIgnoredName('Then'); // WordPress CLI uses @Then in some places.
AnnotationReader::addGlobalIgnoredName('then'); // WordPress CLI uses @then in some places.
AnnotationReader::addGlobalIgnoredName('Given'); // WordPress CLI uses @Given in some places.
AnnotationReader::addGlobalIgnoredName('given'); // WordPress CLI uses @given in some places.

(new WordPressHookAttributes())();
