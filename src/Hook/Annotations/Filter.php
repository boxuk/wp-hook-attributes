<?php

namespace BoxUk\WpHookAttributes\Hook\Annotations;

use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\Hook\FilterInterface;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD", "FUNCTION"})
 */
class Filter extends AbstractHook implements FilterInterface
{
}
