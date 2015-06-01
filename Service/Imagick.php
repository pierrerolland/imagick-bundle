<?php
namespace Rolland\ImagickBundle\Service;

use Rolland\ImagickBundle\Exception\FilterNotFoundException;
use Rolland\ImagickBundle\Exception\OperationNotPermittedException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Asset\UrlPackage;
use Symfony\Component\Templating\Helper\CoreAssetsHelper;

/**
 * Class Imagick
 *
 * @package Rolland\ImagickBundle\Service
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 */
class Imagick
{
    /**
     * @var \Imagick
     */
    protected $image;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var string
     */
    protected $baseAssetUrl;

    /**
     * @var array
     */
    protected $allowedOperations;

    /**
     * @var array
     */
    protected $operations;

    /**
     * @var string
     */
    protected $hashed;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var CoreAssetsHelper
     */
    protected $assetsHelper;

    /**
     * @param array              $filters
     * @param string             $cacheDir
     * @param string             $webDir
     * @param array              $allowedOperations
     * @param ContainerInterface $container
     */
    public function __construct(array $filters, $cacheDir, $webDir, array $allowedOperations, ContainerInterface $container)
    {
        $this->filters = $filters;
        $this->cacheDir = realpath($cacheDir);
        $this->webDir = realpath($webDir);
        $this->baseAssetUrl = str_replace($this->webDir, '', $this->cacheDir);
        $this->allowedOperations = $allowedOperations;
        $this->assetsHelper = $container->get('templating.helper.assets');
        $this->assetsHelper->addPackage('url', new UrlPackage());
    }

    /**
     * Opens an image
     *
     * @param string $fileName
     *
     * @return Imagick
     */
    public function open($fileName)
    {
        $this->image = new \Imagick($this->webDir . $fileName);
        $this->hashed = hash_file('md5', $this->webDir . $fileName);
        $this->operations = array();

        return $this;
    }

    /**
     * Saves the modified image
     *
     * @param string $name
     *
     * @return string
     */
    public function save($name = '')
    {
        if (!$name) {
            $name = hash('md5', implode('', $this->operations));
        }
        $savedName = $this->cacheDir . '/' . $this->hashed . $name;
        $this->image->writeImage($savedName);

        return $this->assetsHelper->getUrl($this->baseAssetUrl . '/' . $this->hashed . $name, 'url');
    }

    /**
     * Gets the \Imagick object
     *
     * @return \Imagick
     */
    public function getObject()
    {
        return $this->image;
    }

    /**
     * Crops the image from the point at ($x, $y) and for a width and height of ($width, $height)
     *
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     *
     * @return Imagick
     */
    public function crop($x, $y, $width, $height)
    {
        $this->image->cropImage($width, $height, $x, $y);
        $this->operations[] = 'crop' . $x . $y . $width . $height;

        return $this;
    }

    /**
     * Applies a vertical mirror on the image
     *
     * @return Imagick
     */
    public function flip()
    {
        $this->image->flipImage();
        $this->operations[] = 'flip';

        return $this;
    }

    /**
     * Applies a horizontal mirror on the image
     *
     * @return Imagick
     */
    public function flop()
    {
        $this->image->flopImage();
        $this->operations[] = 'flip';

        return $this;
    }

    /**
     * Adds a gaussian blur on the image
     *
     * @param float $radius
     * @param float $sigma
     *
     * @return Imagick
     */
    public function gblur($radius, $sigma)
    {
        $this->image->gaussianBlurImage($radius, $sigma);
        $this->operations[] = 'gblur' . $radius . $sigma;

        return $this;
    }

    /**
     * Returns the image grayscaled
     *
     * @return Imagick
     */
    public function grayscale()
    {
        $this->image->transformimagecolorspace(\Imagick::COLORSPACE_GRAY);
        $this->operations[] = 'grayscale';

        return $this;
    }

    /**
     * Negates the image's colors
     *
     * @return Imagick
     */
    public function negate()
    {
        $this->image->negateImage(false);

        return $this;
    }

    /**
     * Changes the image's opacity
     *
     * @param int $opacity
     *
     * @return Imagick
     */
    public function opacity($opacity)
    {
        $this->image->setImageOpacity($opacity);
        $this->operations[] = 'opacity' . $opacity;

        return $this;
    }

    /**
     * Rounds the image's corners
     *
     * @param float $x
     * @param float $y
     *
     * @return Imagick
     */
    public function rcorners($x, $y)
    {
        $this->image->roundCorners($x, $y);
        $this->operations[] = 'rcorners' . $x . $y;

        return $this;
    }

    /**
     * Rotates the image's
     *
     * @param int $angle
     *
     * @return Imagick
     */
    public function rotate($angle)
    {
        $this->image->rotateImage(new \ImagickPixel('#00000000'), $angle);
        $this->operations[] = 'rotate' . $angle;

        return $this;
    }

    /**
     * Sepias the image
     *
     * @return Imagick
     */
    public function sepia()
    {
        $this->image->sepiaToneImage(80);

        return $this;
    }

    /**
     * Strips EXIF data from image
     *
     * @return Imagick
     */
    public function strip()
    {
        $this->image->stripImage();
        $this->operations[] = 'strip';

        return $this;
    }

    /**
     * Thumbnails the image A 0 will keep proportions
     *
     * @param int $x
     * @param int $y
     *
     * @return Imagick
     */
    public function thumb($x, $y)
    {
        $this->image->thumbnailImage($x, $y);
        $this->operations[] = 'thumb' . $x . $y;

        return $this;
    }

    /**
     * @param string $fileName
     * @param string $filterName
     *
     * @return string
     *
     * @throws FilterNotFoundException
     * @throws OperationNotPermittedException
     */
    public function processFilter($fileName, $filterName)
    {
        $cache = $this->retrieveCache($fileName, $filterName);
        if ($cache) {
            return $cache;
        }
        if (!array_key_exists($filterName, $this->filters)) {
            throw new FilterNotFoundException($filterName, array_keys($this->filters));
        }
        $this->open($fileName);
        foreach ($this->filters[$filterName] as $operation => $args) {
            if (!in_array($operation, $this->allowedOperations)) {
                $this->save($filterName);
                throw new OperationNotPermittedException($operation, $this->allowedOperations);
            }
            if (!is_array($args)) {
                $args = array($args);
            }
            call_user_func_array(array($this, $operation), $args);
        }
        return $this->save($filterName);
    }

    /**
     * @param string $fileName
     * @param string $filterName
     *
     * @return bool|string
     */
    protected function retrieveCache($fileName, $filterName)
    {
        $hash = hash_file('md5', $this->webDir . $fileName);
        $cacheName = $this->cacheDir . '/' . $hash . $filterName;
        if (file_exists($cacheName)) {
            return $this->assetsHelper->getUrl($this->baseAssetUrl . '/' . $hash . $filterName, 'url');
        }

        return false;
    }
}