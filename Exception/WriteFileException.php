<?php
namespace Rolland\ImagickBundle\Exception;

/**
 * Exception thrown when an file can't be written
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 */
class WriteFileException extends \Exception
{
    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        parent::__construct('Imagick : can not write file in cache. Is it a permissions issue ? File path : ' . $filePath);
    }
}