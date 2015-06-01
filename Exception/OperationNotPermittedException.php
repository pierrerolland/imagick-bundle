<?php
namespace Rolland\ImagickBundle\Exception;

/**
 * Exception thrown when an unknown operation is being called
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 */
class OperationNotPermittedException extends \Exception
{
    /**
     * @param string $operationName
     * @param array  $allowedOperations
     */
    public function __construct($operationName, array $allowedOperations)
    {
        parent::__construct('Imagick : operation ' . $operationName . ' not permitted. Allowed operations are ' . implode(', ', $allowedOperations));
    }
}