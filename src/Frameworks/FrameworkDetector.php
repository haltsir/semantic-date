<?php

namespace Haltsir\SemanticDate\Frameworks;

class FrameworkDetector
{
    public static function isLaravel(): bool
    {
        return class_exists('\Illuminate\Foundation\Application') && function_exists('app');
    }
}
