<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\WordPressFunctionDoesNotExistException;
use BoxUk\WpHookAttributes\WordPressHookCaller;
use PHPUnit\Framework\TestCase;

class WordPressHookCallerTest extends TestCase
{
    private WordPressHookCaller $wordPressHookCaller;

    protected function setUp(): void
    {
        $this->wordPressHookCaller = new WordPressHookCaller();
    }

    public function test_add_filter_throws_exception_when_function_does_not_exist(): void {
        $this->expectException(WordPressFunctionDoesNotExistException::class);

        $this->wordPressHookCaller->addFilter('the_content', fn() => null);
    }

    public function test_add_action_throws_exception_when_function_does_not_exist(): void {
        $this->expectException(WordPressFunctionDoesNotExistException::class);

        $this->wordPressHookCaller->addAction('init', fn() => null);
    }
}
