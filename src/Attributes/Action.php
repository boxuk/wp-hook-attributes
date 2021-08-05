<?php

namespace BoxUk\WpHookAttributes\Attributes;

use Attribute;
use BoxUk\WpHookAttributes\AbstractHook;
use BoxUk\WpHookAttributes\ActionInterface;

#[Attribute]
class Action extends AbstractHook implements ActionInterface
{

}
