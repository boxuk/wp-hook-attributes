<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Hook\Attributes;

use Attribute;
use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\Hook\ActionInterface;

#[Attribute]
class Action extends AbstractHook implements ActionInterface
{
}
