Installation
============

Defining filters
----------------

The aim here is to define filters in your ``app/config/config.yml``
file. Those filters will then be used in your templates

.. code-block:: yaml

    rolland_imagick:
        filters:
            my_filter:
                opacity: 0.1
            my_other_filter:
                opacity: 0.5

All the available operations are :

.. code-block:: yaml

    opacity: XX # XX is a float value

Using the filters in templates
------------------------------

.. code-block:: twig

    <img src="{{ img|imagick_filter('my_filter') }}" />

"img" should be relative to your web directory

Using the service in PHP code
-----------------------------

.. code-block:: php

    <?php
        $this
            ->get('rolland_imagick.imagick')
            ->open('test.jpg')
            ->opacity(0.6)
            ->save()
        ;
