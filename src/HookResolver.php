<?php

namespace BoxUk\WpHookAttributes;

use BoxUk\WpHookAttributes\Attributes\Action;
use BoxUk\WpHookAttributes\Attributes\Filter;
use BoxUk\WpHookAttributes\Annotations\Action as ActionAnnotation;
use BoxUk\WpHookAttributes\Annotations\Filter as FilterAnnotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;

class HookResolver
{
    /**
     * @var AnnotationReader|Reader|null
     */
    private ?Reader $reader;
    private array $classes;
    private array $functions;

    public function __construct(Reader $reader = null) {
        $this->reader = $reader;
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
            $hook = $attribute instanceof \ReflectionAttribute ? $attribute->newInstance() : $attribute;
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
            $hook = $attribute instanceof \ReflectionAttribute ? $attribute->newInstance() : $attribute;
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

            $actionAttributes = [];
            $filterAttributes = [];
            if (\PHP_VERSION_ID >= 80000) {
                $actionAttributes = $refFunction->getAttributes(Action::class);
                $filterAttributes = $refFunction->getAttributes(Filter::class);
            } elseif ($this->reader instanceof Reader) {
                $actionAnnotation = $this->reader->getFunctionAnnotation($refFunction, ActionAnnotation::class);
                $actionAttributes = $actionAnnotation !== null ? [$actionAnnotation] : [];
                $filterAnnotation = $this->reader->getFunctionAnnotation($refFunction, FilterAnnotation::class);
                $filterAttributes = $filterAnnotation !== null ? [$filterAnnotation] : [];
            }

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
                $actionAttributes = [];
                $filterAttributes = [];
                if (\PHP_VERSION_ID >= 80000) {
                    $actionAttributes = $method->getAttributes(Action::class);
                    $filterAttributes = $method->getAttributes(Filter::class);
                } elseif ($this->reader instanceof Reader) {
                    $actionAnnotation = $this->reader->getMethodAnnotation($method, ActionAnnotation::class);
                    $actionAttributes = $actionAnnotation !== null ? [$actionAnnotation] : [];
                    $filterAnnotation = $this->reader->getMethodAnnotation($method, FilterAnnotation::class);
                    $filterAttributes = $filterAnnotation !== null ? [$filterAnnotation] : [];
                }

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
