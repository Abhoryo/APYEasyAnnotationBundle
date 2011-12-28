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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\KernelEvents;
use APY\EasyAnnotationBundle\Annotation\ContainerAwareEasyAnnotation;

class Template extends ContainerAwareEasyAnnotation
{
    /**
     * The template reference.
     *
     * @var TemplateReference
     */
    protected $template;
    
    /**
     * The template engine used when a specific template isnt specified
     *
     * @var string
     */
    protected $engine = 'twig';
    
    /**
     * The associative array of template variables.
     *
     * @var array
     */
    protected $vars = array();

    public function process($annotationScope, $event, $class, $method)
    {
        if ($annotationScope != self::propertyScope) {
            $request = $event->getRequest();
            $parameters = $event->getControllerResult();

            if (!$this->value) {
                $this->template = $this->value;
            }

            if (!$this->template) {
                $this->template = $this->guessTemplateName($class, $method, $request, $this->engine);
            }

            if (null === $parameters) {
                if (!$default_vars = $this->vars) {
                    // all controller method arguments
                    $reflectionClass = new \ReflectionObject($class);

                    $default_vars = array();
                    foreach ($reflectionClass->getMethod($method)->getParameters() as $param) {
                        $default_vars[] = $param->getName();
                    }

                    if (!$default_vars) {
                        return;
                    }
                }

                $parameters = array();
                foreach ($default_vars as $var) {
                    $parameters[$var] = $request->attributes->get($var);
                }
            }

            if (!is_array($parameters) || !$this->template) {
                return $parameters;
            }

            $event->setResponse(new Response($this->container->get('templating')->render($this->template, $parameters)));
        }
    }

    public function getEvent(){
        return KernelEvents::VIEW;
    }
    
    /**
     * Part of the TemplateListener class handles the @Template annotation.
     * Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener
     *
     * @author     Fabien Potencier <fabien@symfony.com>
     */
    
    /**
     * Guesses and returns the template name to render based on the controller
     * and action names.
     *
     * @param string $class The controller class and action method
     * @param string $method The action method
     * @param Request $request A Request instance
     * @param string $engine
     * @return TemplateReference template reference
     * @throws \InvalidArgumentException
     */
    protected function guessTemplateName($class, $method, Request $request, $engine = 'twig')
    {
        if (!preg_match('/Controller\\\(.+)Controller$/', $class, $matchController)) {
            throw new \InvalidArgumentException(sprintf('The "%s" class does not look like a controller class (it must be in a "Controller" sub-namespace and the class name must end with "Controller")', $class));

        }

        if (!preg_match('/^(.+)Action$/', $method, $matchAction)) {
            throw new \InvalidArgumentException(sprintf('The "%s" method does not look like an action method (it does not end with Action)', $method));
        }

        $bundle = $this->getBundleForClass($class);

        return new TemplateReference($bundle->getName(), $matchController[1], $matchAction[1], $request->getRequestFormat(), $engine);
    }

    /**
     * Returns the Bundle instance in which the given class name is located.
     *
     * @param string $class A fully qualified controller class name
     * @param Bundle $bundle A Bundle instance
     * @throws \InvalidArgumentException
     */
    protected function getBundleForClass($class)
    {
        $namespace = strtr(dirname(strtr($class, '\\', '/')), '/', '\\');
        foreach ($this->container->get('kernel')->getBundles() as $bundle) {
            if (0 === strpos($namespace, $bundle->getNamespace())) {
                return $bundle;
            }
        }

        throw new \InvalidArgumentException(sprintf('The "%s" class does not belong to a registered bundle.', $class));
    }
}
