<?php

/*
 * This file is part of the APYEasyAnnoationBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\EasyAnnotationBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Event;

use APY\EasyAnnotationBundle\Annotation\EasyAnnotation;
use APY\EasyAnnotationBundle\Annotation\ContainerAwareEasyAnnotation;

class EasyAnnotationListener
{
    public $triggerEvent;

    /**
     * @var string The name of the class called in the controller
     */
    protected $class;

    /**
     * @var string The name of the method called in the controller
     */
    protected $method;
    
    /**
     * @var ContainerInterface An ContainerInterface instance
     */
    protected $container;
    
    /**
     * @var Reader An Reader instance
     */
    protected $reader;

    /**
     * Constructor.
     *
     * @param Reader $reader An Reader instance
     * @param ContainerInterface $container An ContainerInterface instance
     */
    public function __construct(Reader $reader, ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->container = $container;
    }

    /**
     * Get the class and method called
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $this->class = get_class($controller[0]);
        $this->method = $controller[1];

        if ($this->triggerEvent == KernelEvents::CONTROLLER) {
            $this->onEvent($event);
        }
    }

    public function onEvent(Event $event)
    {
        if (!$this->class) {
            return;
        }

        // Annotations from class
        $reflectionClass = new \ReflectionClass($this->class);
        if ($reflectionClass->isAbstract()) {
            throw new \InvalidArgumentException(sprintf('Annotations from class "%s" cannot be read as it is abstract.', $reflectionClass));
        }
        $this->processAnnotations($this->reader->getClassAnnotations($reflectionClass), EasyAnnotation::classScope, $event);

        // Annotations from properties
        foreach ($reflectionClass->getProperties() as $reflectionProperty)
        {
            $this->processAnnotations($this->reader->getPropertyAnnotations($reflectionProperty), EasyAnnotation::propertyScope, $event);
        }

        // Annotations from method
        $reflectionMethod = $reflectionClass->getMethod($this->method);
        $this->processAnnotations($this->reader->getMethodAnnotations($reflectionMethod), EasyAnnotation::methodScope, $event);
    }

    private function processAnnotations(array $annotations, $annotationScope, Event $event) {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof EasyAnnotation) {
                if ($this->triggerEvent == $annotation->getEvent()) {
                    if ($annotation instanceof ContainerAwareEasyAnnotation) {
                        $annotation->setContainer($this->container);
                    }
                    $annotation->process($annotationScope, $event, $this->class, $this->method);
                }
            }
        }
    }
}
