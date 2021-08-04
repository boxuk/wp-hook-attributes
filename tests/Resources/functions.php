<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

#[Action('init')]
function basic_action() {
    return 'Basic action works!';
}

#[Filter('the_content')]
function basic_filter() {
    return 'Basic filter works!';
}

#[Action('init', priority: 99)]
function action_with_priority() {
    return 'Action with priority works!';
}

#[Action('init', args: 2)]
function action_with_args($a, $b) {
    return 'Action with args works!';
}

#[Filter('the_content', priority: 99)]
function filter_with_priority() {
    return 'Filter with priority works!';
}

#[Filter('the_content', args: 2)]
function filter_with_args($a, $b) {
    return 'Filter with args works!';
}
