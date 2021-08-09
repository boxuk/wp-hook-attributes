<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * Convenience class to invoke everything we need in a single call.
 * Will return an instance (the same instance) of HookAttributeManager
 *
 * Usage: (new WordPressHookAttributes())();
 */
final class WordPressHookAttributes
{
    private static $instance;

    public function __invoke(): HookAttributesManager
    {
        if (self::$instance instanceof HookAttributesManager) {
            return self::$instance;
        }

        if (\PHP_VERSION_ID >= 80000) {
            $hookResolver = new HookResolver();
        } else {
            $cacheAdapter = apply_filters('wp_hook_attributes_cache_adapter', new ArrayAdapter());
            $annotationReader = new PsrCachedAnnotationReader(new AnnotationReader(), $cacheAdapter);
            $hookResolver = new HookResolver($annotationReader);
        }

        $registeredNamespaces = apply_filters('wp_hook_attributes_registered_namespaces', []);
        foreach ($registeredNamespaces as $registeredNamespace) {
            $hookResolver->registerNamespace($registeredNamespace);
        }

        $hookAttributesManager = new HookAttributesManager($hookResolver, new WordPressHookCaller());
        self::$instance = $hookAttributesManager->init();

        return self::$instance;
    }
}
