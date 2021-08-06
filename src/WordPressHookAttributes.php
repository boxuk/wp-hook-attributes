<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

/**
 * Convenience class to invoke everything we need in a single call.
 * Will return an instance (the same instance) of HookAttributeManager
 *
 * Usage: (new WordPressHookAttributes())();
 */
final class WordPressHookAttributes
{
    use HookResolverFactory;
    use HookCallerFactory;

    private static HookAttributesManager $instance;

    public function __invoke(bool $useComposerClassmap = false, bool $useFakeHookCaller = false): HookAttributesManager
    {
        if (self::$instance instanceof HookAttributesManager) {
            return self::$instance;
        }

        $hookResolver = self::createHookResolver($useComposerClassmap);
        $hookCaller = self::createHookCaller($useFakeHookCaller);

        $hookAttributesManager = new HookAttributesManager($hookResolver, $hookCaller);
        self::$instance = $hookAttributesManager->init();

        return self::$instance;
    }
}
