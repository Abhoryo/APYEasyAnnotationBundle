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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class ContainerAwareEasyAnnotation extends EasyAnnotation implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface A ContainerInterface instance
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the Container associated with this Controller.
     */
    public function getContainer()
    {
        return $this->container;
    }
}
