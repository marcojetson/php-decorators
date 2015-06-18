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
class AbstractDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public $myProperty = 1;

    public function testGetInstance()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $this->assertSame($this, $decorator->getInstance());
    }

    public function testGet()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $this->assertSame(1, $decorator->myProperty);
    }

    public function myMethod()
    {
        return 1;
    }

    public function testCall()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $decorator->expects($this->any())->method('getMethodDecorators')->willReturn([]);
        $this->assertSame(1, $decorator->myMethod());
    }

    public function myDecorator($callable)
    {
        return function () use ($callable) {
            return $callable() + 2;
        };
    }

    public function testCallDecorated()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $decorator->expects($this->any())->method('getMethodDecorators')->willReturn(['Decorator\AbstractDecoratorTest::myDecorator']);
        $this->assertSame(3, $decorator->myMethod());
    }

    public function testResolveCallableMethodString()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $callable = $decorator->resolveCallable('Decorator\AbstractDecoratorTest::testResolveCallableMethodString');

        $this->assertTrue(is_callable($callable));
        $this->assertTrue(is_array($callable));
        $this->assertCount(2, $callable);
        $this->assertInstanceOf('Decorator\AbstractDecoratorTest', $callable[0]);
        $this->assertSame('testResolveCallableMethodString', $callable[1]);
    }

    public function testResolveCallableCallable()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $callable = $decorator->resolveCallable([$this, 'testResolveCallableCallable']);

        $this->assertTrue(is_callable($callable));
        $this->assertTrue(is_array($callable));
        $this->assertCount(2, $callable);
        $this->assertSame($this, $callable[0]);
        $this->assertSame('testResolveCallableCallable', $callable[1]);
    }

    public function __invoke()
    {
    }

    public function testResolveCallableClassString()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $callable = $decorator->resolveCallable('Decorator\AbstractDecoratorTest');

        $this->assertTrue(is_callable($callable));
        $this->assertInstanceOf('Decorator\AbstractDecoratorTest', $callable);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testResolveInvalidCallable()
    {
        $decorator = $this->getMockForAbstractClass('Decorator\AbstractDecorator', [$this]);
        $decorator->resolveCallable(false);
    }
}
