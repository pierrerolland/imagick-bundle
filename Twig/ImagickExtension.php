<?php
namespace Rolland\ImagickBundle\Twig;

use Rolland\ImagickBundle\Exception\FilterNotFoundException;
use Rolland\ImagickBundle\Exception\OperationNotPermittedException;
use Rolland\ImagickBundle\Service\Imagick;

/**
 * Class ImagickExtension
 *
 * @package Rolland\ImagickBundle\Twig
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 *
 */
class ImagickExtension extends \Twig_Extension
{
    /**
     * @var Imagick
     */
    protected $imagick;

    /**
     * @param Imagick $imagick
     */
    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('imagick_filter', array($this, 'filter')),
        );
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
    public function filter($fileName, $filterName)
    {
        return $this->imagick->processFilter($fileName, $filterName);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rolland_imagick_extension';
    }
}