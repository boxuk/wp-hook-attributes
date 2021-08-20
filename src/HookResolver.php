<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes;

use BoxUk\WpHookAttributes\Hook\Attributes\Action;
use BoxUk\WpHookAttributes\Hook\Attributes\Filter;
use BoxUk\WpHookAttributes\Hook\Annotations\Action as ActionAnnotation;
use BoxUk\WpHookAttributes\Hook\Annotations\Filter as FilterAnnotation;
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
    private array $namespaces;
    private array $prefixes;

    public function __construct(Reader $reader = null, ?array $functions = null, ?array $classes = null)
    {
        $this->reader = $reader;
        $this->functions = $functions ?? get_defined_functions()['user'];
        $this->classes = $classes ?? get_declared_classes();
        $this->namespaces = [];
        $this->prefixes = [];
    }

    /**
     * Register a namespace to filter all classes and functions to only ones within that namespace.
     */
    public function registerNamespace(string $namespace): void
    {
        $this->namespaces[] = $namespace;
    }

    /**
     * Register a prefix to filter all classes and functions to only ones with this prefixed.
     */
    public function registerPrefix(string $prefix): void
    {
        $this->prefixes[] = $prefix;
    }

    public function registerFunctionsFile(string $file): void
    {
        $existingFunctions = $this->functions;

        if (!in_array($file, get_included_files(), true)) {
            require_once $file;
        }

        // TODO: Parse the file and extract functions rather rely on this which breaks if a custom list of functions is passed anyway.
        $newFunctions = array_values(array_diff(get_defined_functions()["user"], $existingFunctions));

        if ($newFunctions !== []) {
            foreach ($newFunctions as $newFunction) {
                if (!in_array($newFunction, $this->functions, true)) {
                    $this->functions[] = $newFunction;
                }
            }
        }
    }

    public function registerClass(string $class): void
    {
        if (!in_array($class, $this->classes, true)) {
            $this->classes[] = $class;
        }
    }

    /**
     * Resets all registered namespaces, classes and functions.
     */
    public function reset(): self
    {
        $this->namespaces = [];
        $this->prefixes = [];
        $this->classes = [];
        $this->functions = [];

        return $this;
    }

    public function resolveHooks(): array
    {
        return array_merge($this->resolveFunctionHooks(), $this->resolveClassHooks());
    }

    public function resolveFunctionHooks(): array
    {
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

    public function resolveClassHooks(): array
    {
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

        $functions = $this->functions;
        if ($this->namespaces !== []) {
            $functions = $this->filterByNamespaces($functions, $this->namespaces);
        }

        if ($this->prefixes !== []) {
            $functions = $this->filterByPrefixes($functions, $this->prefixes);
        }
        foreach ($functions as $function) {
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

        $classes = $this->classes;
        if ($this->namespaces !== []) {
            $classes = $this->filterByNamespaces($classes, $this->namespaces);
        }

        if ($this->prefixes !== []) {
            $classes = $this->filterByPrefixes($classes, $this->prefixes);
        }
        foreach ($classes as $class) {
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
                    if (! $method->isStatic()) {
                        throw new MethodIsNotStaticException('Hooks can only be defined as attributes/annotations on static methods');
                    }
                    foreach ($actionAttributes as $actionAttribute) {
                        $attributes[$class . '::' . $method->getName()] = $actionAttribute;
                    }
                }

                if ($filterAttributes !== []) {
                    if (! $method->isStatic()) {
                        throw new MethodIsNotStaticException('Hooks can only be defined as attributes/annotations on static methods');
                    }
                    foreach ($filterAttributes as $filterAttribute) {
                        $attributes[$class . '::' . $method->getName()] = $filterAttribute;
                    }
                }
            }
        }

        return $attributes;
    }

    private function filterByNamespaces(array $functionsOrClasses, array $namespaces)
    {
        $return = [];
        foreach ($namespaces as $namespace) {
            $return[] = array_filter($functionsOrClasses, fn (string $key) => stripos($key, $namespace) !== false);
        }

        // Flatten the array.
        return array_merge([], ...$return);
    }

    private function filterByPrefixes(array $functionsOrClasses, array $prefixes)
    {
        $return = [];
        foreach ($prefixes as $prefix) {
            $return[] = array_filter(
                $functionsOrClasses,
                static function (string $key) use ($prefix) {
                    $functionOrClassParts = explode('\\', $key);

                    return stripos(end($functionOrClassParts), $prefix) === 0;
                }
            );
        }

        // Flatten the array.
        return array_merge([], ...$return);
    }
}
