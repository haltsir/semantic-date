<?php

namespace Haltsir\SemanticDate\DateConditions;

use DateTime;
use DateInterval;
use Haltsir\SemanticDate\Holidays;
use Haltsir\SemanticDate\Translator;

class AroundHoliday implements DateConditionInterface
{
    private const DAYS_AROUND_HOLIDAY = 3;

    public function __construct(private Translator $translator, private Holidays $holidays)
    {
    }

    public function match(DateTime $date, DateInterval $interval): bool
    {
        $holidayDate = $this->holidays->getNearestHoliday($date);
        if ($holidayDate === null) {
            return false;
        }

        $diff = $holidayDate->diff($date)->days;

        return $diff <= self::DAYS_AROUND_HOLIDAY;
    }

    public function getSemanticRepresentation(DateTime $date, DateInterval $interval): string
    {
        $holidayDate = $this->holidays->getNearestHoliday($date);
        $holidayName = $this->holidays->getHolidayName($holidayDate);

        return $this->translator->translate('conditionals.around_holiday', [
            'holiday' => $holidayName
        ]);
    }
}
