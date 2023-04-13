<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class LastMonth implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->sub($interval);

        $startOfLastMonth = (clone $today)->modify('-1 month')->modify('first day of this month');
        $endOfLastMonth = (clone $today)->modify('-1 month')->modify('last day of this month')->setTime(23, 59, 59);

        return $date >= $startOfLastMonth && $date <= $endOfLastMonth;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->translator->translate('conditionals.last_month');
    }
}
