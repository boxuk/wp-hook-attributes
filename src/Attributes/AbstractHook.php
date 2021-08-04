<?php

namespace BoxUk\WpHookAttributes\Attributes;

abstract class AbstractHook
{
    public string $name;
    public string $priority;
    public string $args;

    public function __construct(string $name, int $priority = 10, int $args = 1)
    {
        $this->name = $name;
        $this->priority = $priority;
        $this->args = $args;
    }
}
