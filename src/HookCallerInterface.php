<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

interface HookCallerInterface
{
    public const DEFAULT_PRIORITY = 10;
    public const DEFAULT_ARGS = 1;

    public function addFilter(string $filterName, /*callable*/ $callback, int $priority, int $args): bool;
    public function addAction(string $actionName, /*callable*/ $callback, int $priority, int $args): bool;
}
