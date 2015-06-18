<?php
/*
 * This file is part of the Decorator package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Decorator;
use phpDocumentor\Reflection\DocBlock;

/**
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class AnnotationDecorator extends AbstractDecorator
{
    const TAG_NAME = 'Decorate';

    /**
     * @param string $method
     * @return array
     */
    public function getMethodDecorators($method)
    {
        $reflectionMethod = new \ReflectionMethod($this->getInstance(), $method);
        $docBlock = new DocBlock($reflectionMethod);

        $decorators = [];
        foreach ($docBlock->getTagsByName(static::TAG_NAME) as $tag) {
            $decorators[] = $tag->getContent();
        }

        return $decorators;
    }
}
