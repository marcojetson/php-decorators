<?php
/*
 * This file is part of the Decorator package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Decorator;

/**
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
abstract class AbstractDecorator
{
    /** @var mixed */
    private $instance;

    /**
     * @param mixed $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->getInstance()->$property;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $callable = [$this->getInstance(), $method];

        foreach ($this->getMethodDecorators($method) as $decorator) {
            $callable = call_user_func_array($this->resolveCallable($decorator), [$callable, $this->getInstance()]);
        }

        return call_user_func_array($callable, $args);
    }

    /**
     * @param mixed $decorator
     * @return callable
     * @throws \InvalidArgumentException
     */
    public function resolveCallable($decorator)
    {
        if (is_string($decorator) && strpos($decorator, '::') !== false) {
            list($class, $method) = explode('::', $decorator);

            return [new $class, $method];
        }

        if (is_callable($decorator)) {
            return $decorator;
        }

        if (method_exists($decorator, '__invoke')) {
            return new $decorator();
        }

        throw new \InvalidArgumentException(sprintf('Can not resolve "%s" decorator', $decorator));
    }

    /**
     * @param string $method
     * @return array
     */
    abstract public function getMethodDecorators($method);
}
