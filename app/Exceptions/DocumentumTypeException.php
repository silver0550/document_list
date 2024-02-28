<?php

namespace App\Exceptions;

use Exception;

class DocumentumTypeException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}
