<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Exceptions\InvalidCalendarTypeException;
use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\Holidays;

class Holiday implements DateConditionInterface
{
    public function __construct(private Translator $translator, private Holidays $holidays)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        return $this->holidays->isHoliday($date);
    }

    /**
     * @throws InvalidCalendarTypeException
     */
    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        return $this->holidays->getHolidayName($date);
    }
}
