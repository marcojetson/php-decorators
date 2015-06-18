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
trait AnnotationDecoratorFactoryTrait
{
    /**
     * @return $this
     */
    public static function factory()
    {
        $class = get_called_class();
        $args = func_get_args();

        $reflectionClass  = new \ReflectionClass($class);
        $instance = $reflectionClass->newInstanceArgs($args);

        $decorated = new AnnotationDecorator($instance);

        return $decorated;
    }
}
