<?php

namespace BoxUk\WpHookAttributes\Hook\Annotations;

use BoxUk\WpHookAttributes\Hook\AbstractHook;
use BoxUk\WpHookAttributes\Hook\ActionInterface;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD", "FUNCTION"})
 */
class Action extends AbstractHook implements ActionInterface
{

}
