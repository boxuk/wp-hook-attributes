<?php

namespace BoxUk\WpHookAttributes;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationReader;

trait HookResolverFactory
{
    public static function createHookResolver( bool $useComposerClassmap = true): HookResolver
    {
        $classes = $useComposerClassmap ? self::getClassesInComposerClassMaps() : null;
        if (\PHP_VERSION_ID >= 80000) {
            return new HookResolver(null, null, $classes);
        }
        AnnotationReader::addGlobalIgnoredName('dataprovider'); // Needed to stop phpunit errors (case sensitivity issue).
        return new HookResolver( new AnnotationReader(), null, $classes );
    }

    private static function getClassesInComposerClassMaps(): array
    {
        $classes = [];

        foreach (spl_autoload_functions() as $function) {
            if (!\is_array($function)) {
                continue;
            }

            if ($function[0] instanceof ClassLoader) {
                $classes += array_filter($function[0]->getClassMap());
            }
        }

        return array_keys($classes);
    }
}
