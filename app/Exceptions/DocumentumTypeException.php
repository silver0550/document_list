<?php

namespace App\Exceptions;

use Exception;

class DocumentumTypeException extends Exception
{

    public function __construct()
    {
        parent::__construct(__('error.document_type'));
    }
}
