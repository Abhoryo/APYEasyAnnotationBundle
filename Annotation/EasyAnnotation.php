<?php

/*
 * This file is part of the APYEasyAnnoationBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\EasyAnnotationBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

abstract class EasyAnnotation extends Annotation
{
    // Annotation scope
    const classScope       = 'class';
    const propertyScope    = 'property';
    const methodScope      = 'method';

    /**
     * @param string $annotationScope One of the three annotations scope
     * @param Symfony\Component\EventDispatcher\Event $event A Event instance
     * @param string $class Class name of the controller
     * @param string $method Method name of the controller
     */
    abstract public function process($annotationScope, $event, $class, $method);

    /**
     * Returns the trigger event identifier of the annotation
     *
     * @return string
     */
    abstract public function getEvent();
}
