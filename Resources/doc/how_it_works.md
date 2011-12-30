HOW IT WORKS
============

##Step 1 - Get the class name and the method of the controller

A first listener is connected to the `controller` event of the kernel. The bundle get from this event the name of the class and the method of the called controller.

##Step 2 - Wait for the trigger event

A second listener is connected to the trigger event defined in the easy annotation instance.
Nothing else is done until this event is triggered.

##Step 3 - The trigger event was triggered. Retrieve class, methods and properties annotations

With the previous class and method of the controller, annotations are retrieved with the annotation reader.

##Step 4 - Call the `process` method of the easy annotation

For each annotation retrieved from the controller, this bundle call in order the `process` method of each easy annotation found.

**Note:** Each easy annotation is independent but you can create a service that store each annotation and perform something later. (See BreadCrumb example)

Prototype of the `process` method:

	
	/**
	* @param string $annotationScope One of the three annotations scope
	* @param Symfony\Component\EventDispatcher\Event $event A Event instance
	* @param string $class Class name of the controller
	* @param string $method Method name of the controller
	*/
	public function process($annotationScope, $event, $class, $method);
	

`$annotationScope`: EasyAnnotation::classScope, EasyAnnotation::propertyScope or EasyAnnotation::methodScope.
`$event`: The event object dispatched by the trigger event.