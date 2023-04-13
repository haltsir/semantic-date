<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class Wednesday implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        return $interval->d >= 2 && $interval->d <= 6 && $date->format('N') == 3;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->translator->translate('conditionals.wednesday');
    }
}
