<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

final class WordPressHookCaller implements HookCallerInterface
{
    public function addFilter(string $filterName, /*callable*/ $callback, int $priority = self::DEFAULT_PRIORITY, int $args = self::DEFAULT_ARGS): bool
    {
        if (!function_exists('add_filter')) {
            throw new WordPressFunctionDoesNotExistException('add_filter() doesn\'t exist, have you installed WordPress?');
        }

        return add_filter($filterName, $callback, $priority, $args);
    }

    public function addAction(string $actionName, /*callable*/ $callback, int $priority = self::DEFAULT_PRIORITY, int $args = self::DEFAULT_ARGS): bool
    {
        if (!function_exists('add_action')) {
            throw new WordPressFunctionDoesNotExistException('add_action() doesn\'t exist, have you installed WordPress?');
        }

        return add_action($actionName, $callback, $priority, $args);
    }
}
