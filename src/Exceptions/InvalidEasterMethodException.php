<?php

namespace Haltsir\SemanticDate\Exceptions;

use Exception;
use Throwable;

class InvalidEasterMethodException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid Easter method');
    }
}
