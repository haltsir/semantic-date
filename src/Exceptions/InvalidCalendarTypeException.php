<?php

namespace Haltsir\SemanticDate\Exceptions;

use Exception;

class InvalidCalendarTypeException extends Exception
{
    public function __construct(string $calendarType)
    {
        parent::__construct("Invalid calendar type: {$calendarType}");
    }
}
