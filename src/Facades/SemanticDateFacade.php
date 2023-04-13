<?php

namespace Haltsir\SemanticDate\Facades;

use Illuminate\Support\Facades\Facade;

class SemanticDateFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'semantic-date';
    }
}
