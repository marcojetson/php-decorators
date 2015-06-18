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
class AnnotationDecoratorFactoryTraitTest extends \PHPUnit_Framework_TestCase
{
    use AnnotationDecoratorFactoryTrait;

    public function testFactory()
    {
        $this->assertInstanceOf('Decorator\AnnotationDecorator', static::factory());
    }

    public function testFactoryArguments()
    {
        $this->assertInstanceOf('Decorator\AnnotationDecorator', static::factory('name'));
    }
}
