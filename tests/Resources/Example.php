<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

final class Example
{
    #[Action('init')]
    public function basic_action(): string {
        return 'Basic action works!';
    }

    #[Filter('the_content')]
    public function basic_filter(): string {
        return 'Basic filter works!';
    }

    #[Action('init', priority: 99)]
    public function action_with_priority(): string {
        return 'Action with priority works!';
    }

    #[Action('init', args: 2)]
    public function action_with_args(int $a, int $b): string {
        return 'Action with args works!';
    }

    #[Filter('the_content', priority: 99)]
    public function filter_with_priority(): string {
        return 'Filter with priority works!';
    }

    #[Filter('the_content', args: 2)]
    public function filter_with_args(int $a, int $b): string {
        return 'Filter with args works!';
    }
}
