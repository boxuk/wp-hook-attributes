<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Hook;

abstract class AbstractHook
{
    public string $name;
    public int $priority;
    public int $args;

    public function __construct(string $name, int $priority = 10, int $args = 1)
    {
        $this->name = $name;
        $this->priority = $priority;
        $this->args = $args;
    }
}
