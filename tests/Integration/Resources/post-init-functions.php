<?php

namespace BoxUk\WpHookAttributes\Tests\Integration\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;

/**
 * @ActionAnnotation("wp_loaded")
 */
#[Action('wp_loaded')]
function wp_loaded_action(): void
{
    echo 'on wp_loaded action';
}
