<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\HookResolver;
use BoxUk\WpHookAttributes\Tests\Resources\Example;
use PHPUnit\Framework\TestCase;

class HookResolverTest extends TestCase
{
    use HookResolverFactory;

    private HookResolver $hookResolver;

    protected function setUp(): void
    {
        $this->hookResolver = self::createHookResolver();
    }

    public function test_hooks_are_resolved_on_registered_functions_file(): void
    {
        $this->hookResolver->registerFunctionsFile(__DIR__ . '/Resources/functions.php');
        $hooks = $this->hookResolver->resolveFunctionHooks();

        self::assertCount(6, $hooks); // 6 functions declared in the registered functions file.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_required_functions_file(): void
    {
        require_once __DIR__ . '/Resources/functions-two.php';
        $hookResolver = self::createHookResolver();
        $hooks = $hookResolver->resolveFunctionHooks();

        self::assertCount(12, $hooks); // 6 functions declared in the required functions file + 6 functions declared in the registered functions file (required in the test above).
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_functions_can_be_filtered_by_namespace(): void
    {
        $hookResolver = self::createHookResolver();
        $hookResolver->registerNamespace('BoxUk\WpHookAttributes\Tests\Resources\Sub');
        $hookResolver->registerFunctionsFile(__DIR__ . '/Resources/Sub/functions.php');
        $hooks = $hookResolver->resolveFunctionHooks();

        self::assertCount(6, $hooks); // 6 functions declared in the required functions file.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_autoloaded_class(): void
    {
        $example = new Example();
        $hooks = $this->hookResolver->resolveClassHooks();

        self::assertCount(6, $hooks); // 6 methods declared in the Example class.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_on_registered_class(): void
    {
        require_once __DIR__ . '/Resources/ExampleWithNoNamespace.php';
        $this->hookResolver->registerClass('ExampleWithNoNamespace');
        $hooks = $this->hookResolver->resolveClassHooks();

        self::assertCount(12, $hooks); // 6 methods declared in the Example class (declared in test above) + 6 methods declared from the ExampleWithNoNamespace class.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_classes_can_be_filtered_by_namespace(): void
    {
        $hookResolver = self::createHookResolver();
        $hookResolver->registerNamespace('BoxUk\WpHookAttributes\Tests\Resources\Sub');
        $hookResolver->registerClass(\BoxUk\WpHookAttributes\Tests\Resources\Example::class);
        $hookResolver->registerClass(\BoxUk\WpHookAttributes\Tests\Resources\Sub\Example::class);
        $hooks = $hookResolver->resolveClassHooks();

        self::assertCount(6, $hooks); // 6 functions declared in the Sub Example class.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }

    public function test_hooks_are_resolved_for_both_functions_and_classes(): void
    {
        $hooks = $this->hookResolver->resolveHooks();

        self::assertCount(36, $hooks); // This number is made up of all declared functions and methods, will need to be updated upon addition of a new fixture file.
        self::assertContainsOnlyInstancesOf(AbstractHook::class, array_column($hooks, 'hook'));
    }
}
