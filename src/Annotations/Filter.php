<?php

namespace BoxUk\WpHookAttributes\Annotations;

use BoxUk\WpHookAttributes\AbstractHook;
use BoxUk\WpHookAttributes\FilterInterface;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD", "FUNCTION"})
 */
class Filter extends AbstractHook implements FilterInterface
{

}
