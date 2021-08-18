<?php

namespace BoxUk\WpHookAttributes\Tests\Resources;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;

final class ExampleObject
{
    private $foo = 'world';

    /**
     * @ActionAnnotation("init")
     */
    #[Action('init')]
    public function object_action(): string
    {
        return 'Hello ' . $this->foo;
    }
}
