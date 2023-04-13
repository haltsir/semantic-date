<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;

interface DateConditionInterface
{
    public function match(DateTime $date, DateInterval $interval): bool;

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string;
}
