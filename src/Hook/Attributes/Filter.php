<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Hook\Attributes;

use Attribute;
use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\Hook\FilterInterface;

#[Attribute]
class Filter extends AbstractHook implements FilterInterface
{

}
