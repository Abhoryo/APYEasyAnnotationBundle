<?php

/*
 * This file is part of the APYEasyAnnoationBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\EasyAnnotationBundle\Annotation\Examples;

use Symfony\Component\HttpKernel\KernelEvents;
use APY\EasyAnnotationBundle\Annotation\ContainerAwareEasyAnnotation;

class Breadcrumb extends ContainerAwareEasyAnnotation
{
    protected $title;
    protected $routeName = null;
    protected $routeParameters = array();
    protected $routeAbsolute = false;
    protected $route = null;

    private function initProperties() {
        if (isset($this->value)) {
            $this->title = $this->value;
        }

        if (isset($this->route)) {
            if (is_array($this->route)) {
                foreach ($this->route as $key => $value) {
                    $property = 'route'.ucfirst($key);
                    $this->$property = $value;
                }
            }
            else {
                $this->routeName = $this->route;
            }
        }
    }

    public function process($annotationScope, $event, $class, $method)
    {
        if ($annotationScope != self::propertyScope) {
            $this->initProperties();

            $this->container->get('apy_breadcrumb_trail')
                ->add(
                    $this->title,
                    $this->routeName,
                    $this->routeParameters,
                    $this->routeAbsolute
                );
        }
    }

    public function getEvent(){
        return KernelEvents::CONTROLLER;
    }
}
