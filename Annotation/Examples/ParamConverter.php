<?php

namespace APY\EasyAnnotationBundle\Annotation\Examples;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use APY\EasyAnnotationBundle\Annotation\ContainerAwareEasyAnnotation;

class ParamConverter extends ContainerAwareEasyAnnotation
{
    /**
     * The parameter name.
     *
     * @var string
     */
    protected $name;

    /**
     * The parameter class.
     *
     * @var string
     */
    protected $class;

    /**
     * An array of options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Whether or not the parameter is optional.
     *
     * @var Boolean
     */
    protected $optional = false;
    
    public function process($annotationScope, $event, $class, $method)
    {
        if ($annotationScope != self::propertyScope) {
            if (!$this->value) {
                $this->name = $this->value;
            }
            
            $manager = $this->container->get('sensio_framework_extra.converter.manager');
            $request = $event->getRequest();

            // automatically apply conversion for non-configured objects
            $reflectionMethod = new \ReflectionMethod($class, $method);
            foreach ($reflectionMethod->getParameters() as $param) {
                if (!$param->getClass()) {
                    continue;
                }
                
                $name = $param->getName();
                // the parameter is not set
                if ($name == $this->name && !$request->attributes->has($name)) {
                    $this->optional = $param->isOptional();
                    $manager->apply($request, $this);
                }
            }
        }
    }

    public function getEvent(){
        return KernelEvents::CONTROLLER;
    }   
}
