<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\HookResolver;
use Doctrine\Common\Annotations\AnnotationReader;

trait HookResolverFactory
{
    public static function createHookResolver(): HookResolver
    {
        if (\PHP_VERSION_ID >= 80000) {
            return new HookResolver();
        }

        return new HookResolver(new AnnotationReader());
    }
}
