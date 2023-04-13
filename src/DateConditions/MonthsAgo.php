<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class MonthsAgo implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);
        $monthsDifference = $diff->y * 12 + $diff->m;

        return $monthsDifference >= 1 && $monthsDifference < 12;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);
        $monthsDifference = $diff->y * 12 + $diff->m;

        if ($monthsDifference >= 1 && $monthsDifference < 12) {
            return $this->translator->translate('conditionals.months_ago', ['count' => $monthsDifference]);
        }
    }
}
