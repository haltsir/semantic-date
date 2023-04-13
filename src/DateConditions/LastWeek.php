<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class LastWeek implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->sub($interval);

        $startOfLastWeek = (clone $today)->modify('-1 week')->modify('monday this week');
        $endOfLastWeek = (clone $today)->modify('-1 week')->modify('sunday this week')->setTime(23, 59, 59);

        return $date >= $startOfLastWeek && $date <= $endOfLastWeek;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->translator->translate('conditionals.last_week');
    }
}
