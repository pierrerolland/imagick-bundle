Installation
============

Step 1: Download the Bundle
---------------------------

Add these lines to your ``composer.json``

.. code-block:: json

    "require": [
        "pierrerolland/imagick-bundle": "dev-master"
    ],
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/pierrerolland/imagick-bundle"
        }
    ],

And then run

.. code-block:: bash

    $ composer update pierrerolland/imagick-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Rolland\ImagickBundle\RollandImagickBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Configure the Bundle
----------------------------

Some fields are mandatory, please add them in the ``app/config/config.yml``
file of your project

.. code-block:: yaml

    rolland_imagick:
        cache_dir: %kernel.root_dir%/../web/cache/rolland #required
        web_dir:   %kernel.root_dir%/../web # optional if equal to this default value

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md