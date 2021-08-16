<?php

namespace BoxUk\WpHookAttributes\Tests\Integration\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;

/**
 * @ActionAnnotation("wp")
 */
#[Action('wp')]
function wp_action(): void
{
    echo 'on wp action';
}
