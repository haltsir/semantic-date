<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Translator;

class Yesterday implements DateConditionInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        return $interval->days === 1;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->translator->translate('conditionals.yesterday');
    }
}
