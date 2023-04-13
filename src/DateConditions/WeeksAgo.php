<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class WeeksAgo implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);

        $oneMonthAgo = (clone $today)->modify('-1 month');

        return $diff->days >= 14 && $date > $oneMonthAgo;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);
        $weeksAgo = (int)($diff->days / 7);

        return $this->translator->translate('conditionals.weeks_ago', ['count' => $weeksAgo]);
    }
}
