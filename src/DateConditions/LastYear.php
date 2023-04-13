<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class LastYear implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->sub($interval);

        $startOfLastYear = (clone $today)->modify('-1 year')->modify('first day of january this year');
        $endOfLastYear = (clone $today)->modify('-1 year')->modify('last day of december this year')->setTime(23, 59, 59);

        return $date >= $startOfLastYear && $date <= $endOfLastYear;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->translator->translate('conditionals.last_year');
    }
}
