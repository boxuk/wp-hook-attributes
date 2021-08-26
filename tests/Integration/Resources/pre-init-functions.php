<?php

namespace BoxUk\WpHookAttributes\Tests\Integration\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;

/**
 * @ActionAnnotation("muplugins_loaded")
 */
#[Action('muplugins_loaded')]
function muplugins_loaded_action(): void
{
    echo 'on muplugins_loaded action';
}
