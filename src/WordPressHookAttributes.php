<?php

namespace BoxUk\WpHookAttributes;

class WordPressHookAttributes
{
    public function __invoke(): void
    {
        $hookAttributesManager = new HookAttributesManager(new HookResolver(), new WordPressHookCaller());
        $hookAttributesManager->init();
    }
}
