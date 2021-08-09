<?php

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\HookResolver;
use BoxUk\WpHookAttributes\PsrCachedAnnotationReader;
use BoxUk\WpHookAttributes\Tests\Resources\Sub\Example;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

class PsrCachedAnnotationReaderTest extends TestCase
{
    protected function setUp(): void
    {
        if (\PHP_VERSION_ID >= 80000) {
            self::markTestSkipped('These tests only apply to < PHP8');
        }
    }

    public function test_functions_annotations_are_cached(): void
    {
        $annotationReaderMock = $this->createMock(AnnotationReader::class);

        $cache = new ArrayCachePool();
        $annotationReader = new PsrCachedAnnotationReader($annotationReaderMock, $cache);
        $hookResolver = new HookResolver($annotationReader);
        $hookResolver->registerNamespace('BoxUk\WpHookAttributes\Tests\Resources\Sub');
        $hookResolver->registerFunctionsFile(__DIR__ . '/Resources/Sub/functions.php');

        // First call to getFunctionAnnotations should return 12 as uncached at this point.
        $annotationReaderMock->expects($this->exactly(6))->method('getFunctionAnnotations');
        $hooks = $hookResolver->resolveFunctionHooks();
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));

        // Subsequent call to getFunctionAnnotations should return 0 as cached so shoul return from cache.
        $annotationReaderMock->expects($this->exactly(0))->method('getFunctionAnnotations');
        $hooks = $hookResolver->resolveFunctionHooks();
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_class_annotations_are_cached(): void
    {
        $annotationReaderMock = $this->createMock(AnnotationReader::class);

        $cache = new ArrayCachePool();
        $annotationReader = new PsrCachedAnnotationReader($annotationReaderMock, $cache);
        $hookResolver = new HookResolver($annotationReader);
        $hookResolver->registerNamespace('BoxUk\WpHookAttributes\Tests\Resources\Sub');
        $hookResolver->registerClass(Example::class);

        // First call to getMethodAnnotations should return 6 as uncached at this point.
        $annotationReaderMock->expects($this->exactly(6))->method('getMethodAnnotations');
        $hooks = $hookResolver->resolveClassHooks();
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));

        // Subsequent call to getMethodAnnotations should return 0 as cached so should return from cache.
        $annotationReaderMock->expects($this->exactly(0))->method('getMethodAnnotations');
        $hooks = $hookResolver->resolveClassHooks();
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }
}
