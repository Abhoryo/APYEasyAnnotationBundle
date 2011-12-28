Getting Started With EasyAnnotationBundle
=========================================

This bundle provides an easy way to create annotations for class, methods and properties of a controller.

**Compatibility**: Symfony 2.0+

## Installation

Please follow the steps given [here](https://github.com/Abhoryo/APYEasyAnnotationBundle/blob/master/Resources/doc/installation.md) to install this bundle.

## Usage

#### Create the annotation

	<?php
	namespace MyProject\MyBundle\MyAnnotations;

	use Symfony\Component\HttpKernel\KernelEvents;
	use APY\EasyAnnotationBundle\Annotation\EasyAnnotation;

	class MaxAge extends EasyAnnotation
	{
		public function process($annotationScope, $event, $class, $method)
		{
			if ($annotationScope != self::propertyScope) {
				$response = $event->getResponse();

				$response->setMaxAge($this->value);

				$event->setResponse($response);
			}
		}

		public function getEvent(){
			return KernelEvents::RESPONSE;
		}
	}

#### Use the annotation in a controller

	<?php
	// MyProject\MyBundle\DefaultController.php
	namespace MyProject\MyBundle\Controller;

	use MyProject\MyBundle\MyAnnotations\MaxAge;

	/**
	 * General setting for all actions
	 * @MaxAge("15")
	 */
	class DefaultController
	{

		public function indexAction()
		{
			...
		}

		/**
		 * Specific setting only for this action
		 * @MaxAge("10")
		 */
		public function index2Action()
		{
			...
		}
	}

See full examples [here](https://github.com/Abhoryo/APYEasyAnnotationBundle/blob/master/Annotation/Examples).

 The following documents are available:

* [Installation](https://github.com/Abhoryo/APYEasyAnnotationBundle/blob/master/Resources/doc/installation.md)
* How it works
* Parameters of an annotation
* Trigger Event
* Annotation scope

## TODO

* Docs