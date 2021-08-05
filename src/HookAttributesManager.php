<?php

namespace BoxUk\WpHookAttributes;

/**
 * Co-ordinates the resolving of hooks from class methods/functions and calls the necessary hooks.
 */
final class HookAttributesManager
{
    private HookResolver $hookResolver;
    private HookCallerInterface $hookCaller;

    public function __construct(HookResolver $hookResolver, HookCallerInterface $hookCaller)
    {
        $this->hookResolver = $hookResolver;
        $this->hookCaller = $hookCaller;
    }

    public function init(): self
    {
        $hooks = $this->hookResolver->resolveHooks();
        foreach($hooks as $hook) {
            if ($hook['hook'] instanceof ActionInterface) {
                $this->hookCaller->addAction($hook['hook']->name, $hook['callback'], $hook['hook']->priority, $hook['hook']->args);
            }

            if ($hook['hook'] instanceof FilterInterface) {
                $this->hookCaller->addFilter($hook['hook']->name, $hook['callback'], $hook['hook']->priority, $hook['hook']->args);
            }
        }

        return $this;
    }

    public function getHookResolver(): HookResolver
    {
        return $this->hookResolver;
    }
}
