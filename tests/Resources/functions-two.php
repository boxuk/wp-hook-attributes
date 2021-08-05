<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;
use BoxUk\WpHookAttributes\Annotations\Action as ActionAnnotation;
use BoxUk\WpHookAttributes\Annotations\Filter as FilterAnnotation;

/**
 * @ActionAnnotation("init")
 */
#[Action('init')]
function basic_action_two(): string {
    return 'Basic action works!';
}

/**
 * @FilterAnnotation("the_content")
 */
#[Filter('the_content')]
function basic_filter_two(): string {
    return 'Basic filter works!';
}

/**
 * @ActionAnnotation("init", priority="99")
 */
#[Action('init', priority: 99)]
function action_with_priority_two(): string {
    return 'Action with priority works!';
}

/**
 * @ActionAnnotation("init", args="2")
 */
#[Action('init', args: 2)]
function action_with_args_two(int $a, int $b): string {
    return 'Action with args works!';
}

/**
 * @FilterAnnotation("the_content", priority="99")
 */
#[Filter('the_content', priority: 99)]
function filter_with_priority_two(): string {
    return 'Filter with priority works!';
}

/**
 * @FilterAnnotation("the_content", args="2")
 */
#[Filter('the_content', args: 2)]
function filter_with_args_two(int $a, int $b): string {
    return 'Filter with args works!';
}
