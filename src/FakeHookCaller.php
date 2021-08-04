<?php

namespace BoxUk\WpHookAttributes;

final class FakeHookCaller implements HookCallerInterface
{
    private array $calledFilters = [];
    private array $calledActions = [];

    public function addFilter(string $filterName, /*callable*/ $callback, int $priority, int $args): bool
    {
        $this->calledFilters[$filterName][] = [
            'callback' => $callback,
            'priority' => $priority,
            'args' => $args,
        ];

        return true;
    }

    public function addAction(string $actionName, /*callable*/ $callback, int $priority, int $args): bool
    {
        $this->calledActions[$actionName][] = [
            'callback' => $callback,
            'priority' => $priority,
            'args' => $args,
        ];

        return true;
    }

    public function getCalledFilters(): array
    {
        return $this->calledFilters;
    }

    public function getCalledActions(): array
    {
        return $this->calledActions;
    }
}
