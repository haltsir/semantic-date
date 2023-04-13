<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class YearsAgo implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);

        return $diff->days > 730;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        $today = (clone $date)->add($interval);
        $diff = $today->diff($date);
        $yearsAgo = (int)($diff->days / 365);

        return $this->translator->translate('conditionals.years_ago', ['count' => $yearsAgo]);
    }
}
