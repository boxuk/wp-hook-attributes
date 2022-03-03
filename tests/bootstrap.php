<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

AnnotationReader::addGlobalIgnoredName('dataprovider'); // Needed to stop phpunit errors (case sensitivity issue).
AnnotationReader::addGlobalIgnoredName('note'); // Needed to stop phpunit errors (case sensitivity issue).
AnnotationReader::addGlobalIgnoredName('ClassName::CONST_NAME'); // Needed to stop phpunit errors.

$is_integration = isset($GLOBALS['argv'][2]) && $GLOBALS['argv'][2] === 'integration';

if ($is_integration) {
    if (is_readable(__DIR__ . '/integration/.env')) {
        $dotenv = new Dotenv();
        $dotenv->usePutenv(true);
        $dotenv->load(__DIR__ . '/integration/.env');
    }

    $tests_dir = getenv('WP_PHPUNIT__DIR');

    require_once $tests_dir . '/includes/functions.php';

    tests_add_filter('muplugins_loaded', function () {
        require_once __DIR__ . '/Integration/Resources/pre-init-functions.php';
        require_once __DIR__ . '/Integration/Resources/post-init-functions.php';
    }, 0);

    require $tests_dir . '/includes/bootstrap.php';
    require __DIR__ . '/../wp-hook-attributes.php';
}
