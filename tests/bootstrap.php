<?php

use Doctrine\Common\Annotations\AnnotationReader;

require_once __DIR__ . '/../vendor/autoload.php';

AnnotationReader::addGlobalIgnoredName('dataprovider'); // Needed to stop phpunit errors (case sensitivity issue).
