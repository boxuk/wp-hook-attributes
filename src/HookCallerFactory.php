<?php

namespace BoxUk\WpHookAttributes;

trait HookCallerFactory
{
    public static function createHookCaller( bool $useFakeHookCaller = false): HookCallerInterface
    {
        return $useFakeHookCaller === true ? new FakeHookCaller() : new WordPressHookCaller();
    }
}
