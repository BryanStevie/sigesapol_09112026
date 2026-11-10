<?php

namespace App\Exceptions;

use Exception;

class SigesapolException extends Exception
{
    /**
     * Código HTTP asociado.
     * @var int
     */
    private $status;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int    $status
     */
    public function __construct($message = "", $status = 400)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    /**
     * Retorna HTTP status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
