<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Doctrine\Common\Annotations\AnnotationReader;

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
            $cacheAdapter = apply_filters('wp_hook_attributes_cache_adapter', new ArrayCachePool());
            $annotationReader = new PsrCachedAnnotationReader(new AnnotationReader(), $cacheAdapter);
            $hookResolver = new HookResolver($annotationReader);
        }

        $registeredNamespaces = apply_filters('wp_hook_attributes_registered_namespaces', []);
        foreach ($registeredNamespaces as $registeredNamespace) {
            $hookResolver->registerNamespace($registeredNamespace);
        }

        $registeredFunctionFiles = apply_filters('wp_hook_attributes_registered_function_files', []);
        foreach ($registeredFunctionFiles as $registeredFunctionFile) {
            $hookResolver->registerFunctionsFile($registeredFunctionFile);
        }

        $registeredClasses = apply_filters('wp_hook_attributes_registered_classes', []);
        foreach ($registeredClasses as $registeredClass) {
            $hookResolver->registerClass($registeredClass);
        }

        $hookAttributesManager = new HookAttributesManager($hookResolver, new WordPressHookCaller());
        self::$instance = $hookAttributesManager->init();

        return self::$instance;
    }
}
