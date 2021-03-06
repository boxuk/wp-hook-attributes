<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Attributes\Filter;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;
use BoxUk\WpHookAttributes\Hook\Annotations\Filter as FilterAnnotation;

final class Example
{
    /**
     * @ActionAnnotation("init")
     */
    #[Action('init')]
    public static function basic_action(): string
    {
        return 'Basic action works!';
    }

    /**
     * @FilterAnnotation("the_content")
     */
    #[Filter('the_content')]
    public static function basic_filter(): string
    {
        return 'Basic filter works!';
    }

    /**
     * @ActionAnnotation("init", priority="99")
     */
    #[Action('init', priority: 99)]
    public static function action_with_priority(): string
    {
        return 'Action with priority works!';
    }

    /**
     * @ActionAnnotation("init", args="2")
     */
    #[Action('init', args: 2)]
    public static function action_with_args(int $a, int $b): string
    {
        return 'Action with args works!';
    }

    /**
     * @FilterAnnotation("the_content", priority="99")
     */
    #[Filter('the_content', priority: 99)]
    public static function filter_with_priority(): string
    {
        return 'Filter with priority works!';
    }

    /**
     * @FilterAnnotation("the_content", args="2")
     */
    #[Filter('the_content', args: 2)]
    public static function filter_with_args(int $a, int $b): string
    {
        return 'Filter with args works!';
    }
}
