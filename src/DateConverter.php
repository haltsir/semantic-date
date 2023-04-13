<?php

namespace Haltsir\SemanticDate;

use DateTime;
use Exception;
use Haltsir\SemanticDate\DateConditions\DateConditionInterface;
use Haltsir\SemanticDate\Frameworks\FrameworkDetector;

class DateConverter
{
    private Translator $translator;
    private Holidays $holidays;
    private array $dateConditions;
    private array $configuration;

    public function __construct(
        array $configuration = [],
        string $locale = 'en'
    )
    {
        $locale = $locale ?? (FrameworkDetector::isLaravel() ? app()->getLocale() : 'en');

        $this->configuration = array_merge([
            'customHolidays' => [],
            'excludedDateConditions' => [],
            'calendarType' => CalendarType::ORTHODOX,
        ], $configuration);

        $this->translator = new Translator($locale);
        $this->holidays = new Holidays(
            $this->translator,
            $locale,
            $this->configuration['customHolidays'],
            $this->configuration['calendarType']
        );
        $this->dateConditions = $this->getDateConditions($this->configuration['excludedDateConditions']);
    }

    /**
     * @throws Exception
     */
    public function convert(DateTime $date): string
    {
        $today = new DateTime();
        $interval = $today->diff($date);

        foreach ($this->dateConditions as $condition) {
            if ($condition->match($date, $interval)) {
                return $condition->getSemanticRepresentation($date, $interval);
            }
        }

        return $date->format('Y-m-d');
    }

    private function getDateConditions(?array $excludedDateConditions = null): array
    {
        $dateConditions = [
            new DateConditions\Holiday($this->translator, $this->holidays),
            new DateConditions\AroundHoliday($this->translator, $this->holidays),
            new DateConditions\Today($this->translator),
            new DateConditions\Yesterday($this->translator),
            new DateConditions\Monday($this->translator),
            new DateConditions\Tuesday($this->translator),
            new DateConditions\Wednesday($this->translator),
            new DateConditions\Thursday($this->translator),
            new DateConditions\Friday($this->translator),
            new DateConditions\Saturday($this->translator),
            new DateConditions\Sunday($this->translator),
            new DateConditions\LastWeek($this->translator),
            new DateConditions\WeeksAgo($this->translator),
            new DateConditions\LastMonth($this->translator),
            new DateConditions\MonthsAgo($this->translator),
            new DateConditions\LastYear($this->translator),
            new DateConditions\YearsAgo($this->translator),
        ];

        if ($excludedDateConditions !== null) {
            $dateConditions = array_filter(
                $dateConditions,
                fn(DateConditionInterface $condition) => !in_array(
                    get_class($condition),
                    $excludedDateConditions,
                    true
                )
            );
        }

        return $dateConditions;
    }
}
