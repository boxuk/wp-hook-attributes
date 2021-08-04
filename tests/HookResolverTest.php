<?php

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\Attributes\AbstractHook;
use BoxUk\WpHookAttributes\HookResolver;
use BoxUk\WpHookAttributes\Tests\Resources\Example;
use PHPUnit\Framework\TestCase;

class HookResolverTest extends TestCase
{
    public function test_hooks_are_resolved_on_registered_functions_file(): void {
        $hookResolver = new HookResolver();
        $hookResolver->registerFunctionsFile(__DIR__ . '/resources/functions.php');
        $hooks = $hookResolver->resolveFunctionHooks();

        self::assertCount(6, $hooks); // 6 functions declared in the registered functions file.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_required_functions_file(): void {
        require_once __DIR__ . '/resources/functions-two.php';
        $hookResolver = new HookResolver();
        $hooks = $hookResolver->resolveFunctionHooks();

        self::assertCount(12, $hooks); // 6 functions declared in the required functions file + 6 functions declared in the registered functions file (required in the test above).
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_autoloaded_class(): void {
        $example = new Example();
        $hookResolver = new HookResolver();
        $hooks = $hookResolver->resolveClassHooks();

        self::assertCount(6, $hooks); // 6 methods declared in the Example class.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_registered_class(): void {
        $hookResolver = new HookResolver();
        require_once __DIR__ . '/resources/ExampleWithNoNamespace.php';
        $hookResolver->registerClass('ExampleWithNoNamespace');
        $hooks = $hookResolver->resolveClassHooks();

        self::assertCount(12, $hooks); // 6 methods declared in the Example class (declared in test above) + 6 methods declared from the ExampleWithNoNamespace class.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_for_both_functions_and_classes(): void {
        $hookResolver = new HookResolver();
        $hooks = $hookResolver->resolveHooks();

        self::assertCount(24, $hooks); // 6 functions declared in the functions files (required in test above) + 6 functions declared in the registered function file (required in test above) + 6 methods declared in the Example class (declared in test above) + 6 methods declared in the ExampleWithNoNamespace class (declared in test above).
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }
}
