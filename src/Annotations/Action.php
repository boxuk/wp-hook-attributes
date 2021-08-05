<?php

namespace BoxUk\WpHookAttributes\Annotations;

use BoxUk\WpHookAttributes\AbstractHook;
use BoxUk\WpHookAttributes\ActionInterface;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD", "FUNCTION"})
 */
class Action extends AbstractHook implements ActionInterface
{

}
