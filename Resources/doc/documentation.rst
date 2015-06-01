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
                thumb: {x:100, y:0}
                opacity: 0.1
            my_other_filter:
                strip: ~

All the available operations are :

.. code-block:: yaml

    crop: {x:X, y:Y, width:W, height:H} # Crops from point at (X,Y) for a width of W and a height of H
    flip: ~ # Applies a vertical mirror
    flop: ~ # Applies a horizontal mirror
    gblur: {radius: R, sigma:S} # Applies a gaussian blur. R and S are floats
    grayscale: ~ # Changes image to grayscale
    negate: ~ # Negates the image's colors
    opacity: X # Changes the opacity. X is a float
    rcorners: {x:X, y:Y} # Rounds the corners. X and Y are floats
    rotate: X # Rotates the images. X is a float (degrees)
    sepia: ~ # Renders the image in sepia tones
    strip: ~ # Strips the EXIF information
    thumb: {x:X, y:Y) # Thumbnails an image. X and Y are floats. Pass 0 to keep proportions

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

.. code-block:: php

    <?php
        $this->get('rolland_imagick.imagick')->processFilter('test.jpg', 'my_filter');

Using more Imagick methods
--------------------------

The current service is not exhaustive in comparison of all the possibilities Imagick provides.
You can use the Imagick object, though.

.. code-block:: php

    <?php
        $imagick = $this->get('rolland_imagick.imagick');

        $imagick
            ->open('test.jpg')
            ->getObject();
            ->resetImagePage(1)
        ;

        $imagick->save();