<?php

namespace BoxUk\WpHookAttributes;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;

class HookResolver
{
    private array $classes;
    private array $functions;

    public function __construct() {
        $this->functions = get_defined_functions()['user'];
        $this->classes = get_declared_classes();
    }

    public function registerFunctionsFile(string $file): void {
        $existingFunctions = $this->functions;

        if (!in_array($file, get_included_files(), true)) {
            require_once $file;
        }
        $newFunctions = array_values(array_diff(get_defined_functions()["user"], $existingFunctions));

        if ($newFunctions !== []) {
            foreach($newFunctions as $newFunction) {
                if (!in_array($newFunction, $this->functions, true)) {
                    $this->functions[] = $newFunction;
                }
            }
        }
    }

    public function registerClass(string $class): void {
        if (!in_array($class, $this->classes, true)) {
            $this->classes[] = $class;
        }
    }

    public function resolveHooks(): array {
        return array_merge($this->resolveFunctionHooks(), $this->resolveClassHooks());
    }

    public function resolveFunctionHooks(): array {
        $functionAttributes = $this->getFunctionAttributes();

        $attributes = $functionAttributes;

        $hooks = [];
        foreach ($attributes as $functionName => $attribute) {
            $hook = $attribute->newInstance();
            $hooks[] = [
                'callback' => $functionName,
                'hook' => $hook
            ];
        }

        return $hooks;
    }

    public function resolveClassHooks(): array {
        $classAttributes = $this->getClassAttributes();

        $attributes = $classAttributes;

        $hooks = [];
        foreach ($attributes as $methodName => $attribute) {
            $hook = $attribute->newInstance();
            $hooks[] = [
                'callback' => $methodName,
                'hook' => $hook
            ];
        }

        return $hooks;
    }

    /**
     * Get attributes for functions.
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getFunctionAttributes(): array
    {
        $attributes = [];
        foreach ($this->functions as $function) {
            $refFunction = new \ReflectionFunction($function);

            $actionAttributes = $refFunction->getAttributes(Action::class);
            $filterAttributes = $refFunction->getAttributes(Filter::class);

            if ($actionAttributes !== []) {
                foreach ($actionAttributes as $actionAttribute) {
                    $attributes[$refFunction->getName()] = $actionAttribute;
                }
            }

            if ($filterAttributes !== []) {
                foreach ($filterAttributes as $filterAttribute) {
                    $attributes[$refFunction->getName()] = $filterAttribute;
                }
            }
        }

        return $attributes;
    }

    /**
     * Get attributes for classes.
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getClassAttributes(): array
    {
        $attributes = [];
        foreach ($this->classes as $class) {
            $refClass = new \ReflectionClass($class);

            foreach ($refClass->getMethods() as $method) {
                $actionAttributes = $method->getAttributes(Action::class);
                $filterAttributes = $method->getAttributes(Filter::class);

                if ($actionAttributes !== []) {
                    foreach ($actionAttributes as $actionAttribute) {
                        $attributes[$class . '::' . $method->getName()] = $actionAttribute;
                    }
                }

                if ($filterAttributes !== []) {
                    foreach ($filterAttributes as $filterAttribute) {
                        $attributes[$class . '::' . $method->getName()] = $filterAttribute;
                    }
                }
            }
        }

        return $attributes;
    }
}
