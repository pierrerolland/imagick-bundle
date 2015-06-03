<?php
namespace Rolland\ImagickBundle\Exception;

/**
 * Exception thrown when an file can't be opened
 *
 * @author Pierre Rolland <roll.pierre@gmail.com>
 */
class FileNotFoundException extends \Exception
{
    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        parent::__construct('Imagick : file ' . $fileName . ' not found');
    }
}