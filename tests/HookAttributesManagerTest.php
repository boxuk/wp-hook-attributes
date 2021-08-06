<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\FakeHookCaller;
use BoxUk\WpHookAttributes\HookAttributesManager;
use BoxUk\WpHookAttributes\HookCallerInterface;
use BoxUk\WpHookAttributes\HookResolver;
use BoxUk\WpHookAttributes\HookResolverFactory;
use BoxUk\WpHookAttributes\Tests\Resources\Example;
use PHPUnit\Framework\TestCase;

class HookAttributesManagerTest extends TestCase
{
    use HookResolverFactory;

    private HookCallerInterface $hookCaller;
    private HookResolver $hookResolver;
    private HookAttributesManager $hookAttributesManager;

    protected function setUp(): void
    {
        $this->hookCaller = new FakeHookCaller();
        $this->hookResolver = self::createHookResolver();
        $this->hookAttributesManager = new HookAttributesManager($this->hookResolver, $this->hookCaller);
    }

    public function test_init_calls_expected_filters(): void
    {
        $example = new Example();

        $expectedFilters = [
            'the_content' => [
                [
                    'callback' => Example::class . '::basic_filter',
                    'priority' => 10,
                    'args' => 1,
                ],
                [
                    'callback' => Example::class . '::filter_with_priority',
                    'priority' => 99,
                    'args' => 1,
                ],
                [
                    'callback' => Example::class . '::filter_with_args',
                    'priority' => 10,
                    'args' => 2,
                ],
            ],
        ];

        $this->hookResolver->registerClass(Example::class);
        $this->hookAttributesManager->init();
        self::assertEquals($expectedFilters, $this->hookCaller->getCalledFilters());
    }

    public function test_init_calls_expected_actions(): void
    {
        $example = new Example();

        $expectedActions = [
            'init' => [
                [
                    'callback' => Example::class . '::basic_action',
                    'priority' => 10,
                    'args' => 1,
                ],
                [
                    'callback' => Example::class . '::action_with_priority',
                    'priority' => 99,
                    'args' => 1,
                ],
                [
                    'callback' => Example::class . '::action_with_args',
                    'priority' => 10,
                    'args' => 2,
                ],
            ],
        ];

        $this->hookResolver->registerClass(Example::class);
        $this->hookAttributesManager->init();
        self::assertEquals($expectedActions, $this->hookCaller->getCalledActions());
    }
}
