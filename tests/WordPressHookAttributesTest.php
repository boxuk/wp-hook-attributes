<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests;

use BoxUk\WpHookAttributes\WordPressHookAttributes;
use PHPUnit\Framework\TestCase;

class WordPressHookAttributesTest extends TestCase
{
    public function test_same_instance_can_be_invoked_multiple_times(): void {
        $instance = (new WordPressHookAttributes())(false, true);
        $instance_two = (new WordPressHookAttributes())(false, true);

        self::assertSame($instance, $instance_two);
    }
}
