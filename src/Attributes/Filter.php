<?php

namespace BoxUk\WpHookAttributes\Attributes;

use Attribute;
use BoxUk\WpHookAttributes\AbstractHook;
use BoxUk\WpHookAttributes\FilterInterface;

#[Attribute]
class Filter extends AbstractHook implements FilterInterface
{

}
