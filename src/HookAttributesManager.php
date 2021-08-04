<?php

namespace BoxUk\WpHookAttributes;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

final class HookAttributesManager
{
    private HookResolver $hookResolver;
    private HookCallerInterface $hookCaller;

    public function __construct(HookResolver $hookResolver, HookCallerInterface $hookCaller)
    {
        $this->hookResolver = $hookResolver;
        $this->hookCaller = $hookCaller;
    }

    public function init(): void
    {
        $hooks = $this->hookResolver->resolveHooks();
        foreach($hooks as $hook) {
            if ($hook['hook'] instanceof Action) {
                $this->hookCaller->addAction($hook['hook']->name, $hook['callback'], $hook['hook']->priority, $hook['hook']->args);
            }

            if ($hook['hook'] instanceof Filter) {
                $this->hookCaller->addFilter($hook['hook']->name, $hook['callback'], $hook['hook']->priority, $hook['hook']->args);
            }
        }
    }
}
