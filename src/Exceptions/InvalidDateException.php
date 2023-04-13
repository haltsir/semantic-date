<?php

namespace Haltsir\SemanticDate\Exceptions;

use Exception;

class InvalidDateException extends Exception
{
    public function __construct(string $holidayDate)
    {
        parent::__construct("Invalid holiday date: {$holidayDate}");
    }
}
