Installation
============

## Step 1: Download EasyAnnotationBundle

Ultimately, the EasyAnnotationBundle files should be downloaded to the
`vendor/bundles/APY/EasyAnnotationBundle` directory.

This can be done in several ways, depending on your preference. The first
method is the standard Symfony2 method.

**Using the vendors script**

Add the following lines in your `deps` file:

```
[EasyAnnotationBundle]
    git=git://github.com/Abhoryo/APYEasyAnnotationBundle.git
    target=bundles/APY/EasyAnnotationBundle
```

Now, run the vendors script to download the bundle:

```bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, the run the following:

```bash
$ git submodule add git://github.com/Abhoryo/APYEasyAnnotationBundle.git vendor/bundles/APY/EasyAnnotationBundle
$ git submodule update --init
```

## Step 2: Configure the Autoloader

Add the `APY` namespace to your autoloader:

```php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'APY' => __DIR__.'/../vendor/bundles',
));
```

## Step 3: Enable the bundles

Finally, enable the bundles in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new APY\EasyAnnotationBundle\APYEasyAnnotationBundle(),
    );
}
```
