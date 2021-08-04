<?php

namespace BoxUk\WpHookAttributes;

interface HookCallerInterface
{
    public function addFilter(string $filterName, /*callable*/ $callback, int $priority, int $args): bool;
    public function addAction(string $actionName, /*callable*/ $callback, int $priority, int $args): bool;
}
