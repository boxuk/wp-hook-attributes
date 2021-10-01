<?php

namespace BoxUk\WpHookAttributes\Tests\Integration\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;

final class Example
{
    /**
     * @ActionAnnotation("init")
     */
    #[Action('init')]
    public static function init_action(): void
    {
        echo 'on init action';
    }
}
