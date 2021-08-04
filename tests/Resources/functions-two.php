<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

#[Action('init')]
function basic_action_two() {
    return 'Basic action works!';
}

#[Filter('the_content')]
function basic_filter_two() {
    return 'Basic filter works!';
}

#[Action('init', priority: 99)]
function action_with_priority_two() {
    return 'Action with priority works!';
}

#[Action('init', args: 2)]
function action_with_args_two($a, $b) {
    return 'Action with args works!';
}

#[Filter('the_content', priority: 99)]
function filter_with_priority_two() {
    return 'Filter with priority works!';
}

#[Filter('the_content', args: 2)]
function filter_with_args_two($a, $b) {
    return 'Filter with args works!';
}
