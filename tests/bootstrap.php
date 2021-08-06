<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;

require_once __DIR__ . '/../vendor/autoload.php';

AnnotationReader::addGlobalIgnoredName('dataprovider'); // Needed to stop phpunit errors (case sensitivity issue).
AnnotationReader::addGlobalIgnoredName('note'); // Needed to stop phpunit errors (case sensitivity issue).
