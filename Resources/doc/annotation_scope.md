Annotation Scope
================

Three differents scopes for an annotation:


* EasyAnnotation::classScope = 'class'
* EasyAnnotation::propertyScope = 'property'
* EasyAnnotation::methodScope = 'method'

-

	<?php
	// MyProject\MyBundle\DefaultController.php
	namespace MyProject\MyBundle\Controller;

	use MyProject\MyBundle\MyAnnotations\MyAnnotation;

	/**
	 * Class scope annotations
	 * @MyAnnotation(12)
	 * @MyAnnotation("text")
	 */
	class DefaultController
	{
		/**
		 * Property scope annotations
		 * @MyAnnotation(34)
		 * @MyAnnotation("string")
		 */
		protected myProperty;

		/**
		 * Method scope annotations
		 * @MyAnnotation(56)
                 * @MyAnnotation(value="string")
		 */
		public function index2Action()
		{
			...
		}
	}
