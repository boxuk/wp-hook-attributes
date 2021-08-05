<?php

namespace BoxUk\WpHookAttributes;

class WordPressHookAttributes
{
    use HookResolverFactory;

    public function __invoke(bool $useComposerClassmap = true): HookAttributesManager
    {
        $hookResolver = self::createHookResolver($useComposerClassmap);
        $hookAttributesManager = new HookAttributesManager($hookResolver, new WordPressHookCaller());
        return $hookAttributesManager->init();
    }
}
