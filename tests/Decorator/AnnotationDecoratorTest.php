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
class AnnotationDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $instance = new AnnotationDecorator($this);
        $this->assertInstanceOf('Decorator\AnnotationDecorator', $instance);
        $this->assertInstanceOf('Decorator\AbstractDecorator', $instance);
    }

    /**
     * @Decorate MyDecorator
     */
    public function testGetMethodDecorators()
    {
        $instance = new AnnotationDecorator($this);
        $this->assertSame(['MyDecorator'], $instance->getMethodDecorators('testGetMethodDecorators'));
    }

    /** @Decorate MyDecorator */
    public function testGetMethodDecoratorsInline()
    {
        $instance = new AnnotationDecorator($this);
        $this->assertSame(['MyDecorator'], $instance->getMethodDecorators('testGetMethodDecoratorsInline'));
    }

    /**
     * @Decorate MyDecorator
     * @return self
     */
    public function testGetMethodDecoratorsMixed()
    {
        $instance = new AnnotationDecorator($this);
        $this->assertSame(['MyDecorator'], $instance->getMethodDecorators('testGetMethodDecoratorsMixed'));

        return $this;
    }

    /**
     * @Decorate MyDecorator
     * @Decorate MyDecorator2
     */
    public function testGetMethodDecoratorsMany()
    {
        $instance = new AnnotationDecorator($this);
        $this->assertSame(['MyDecorator', 'MyDecorator2'], $instance->getMethodDecorators('testGetMethodDecoratorsMany'));
    }
}
