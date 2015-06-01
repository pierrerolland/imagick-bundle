<?php
namespace Rolland\ImagickBundle\Exception;

/**
 * Exception thrown when an unknown filter is being called
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 */
class FilterNotFoundException extends \Exception
{
    /**
     * @param string $filterName
     * @param array  $filters
     */
    public function __construct($filterName, array $filters)
    {
        parent::__construct('Imagick : filter ' . $filterName . ' has not been defined in configuration. Defined filters are ' . implode(', ', $filters));
    }
}