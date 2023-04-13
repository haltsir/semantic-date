<?php

namespace Haltsir\SemanticDate;

use DateInterval;
use DateTime;
use Exception;
use Haltsir\SemanticDate\Exceptions\InvalidCalendarTypeException;
use Haltsir\SemanticDate\Exceptions\InvalidDateException;
use Haltsir\SemanticDate\Exceptions\InvalidEasterMethodException;
use Haltsir\SemanticDate\Frameworks\FrameworkDetector;

class Holidays
{
    private string $calendarType;
    private Translator $translator;
    private string $locale;
    private array $customHolidays;
    private array $defaultHolidays;
    private mixed $easterMethod;

    public function __construct(
        Translator $translator,
        string $locale = 'en',
        array $customHolidays = [],
        string $calendarType = CalendarType::ORTHODOX,
        $easterMethod = null
    )
    {
        $this->translator = $translator;
        $this->locale = $locale;
        $this->customHolidays = $customHolidays;
        $this->calendarType = $calendarType;
        $this->easterMethod = $easterMethod;
        $this->defaultHolidays = $this->loadDefaultHolidays();
    }

    public function isHoliday(DateTime $date): bool
    {
        $dayMonth = $date->format('d-m');

        return $this->isDefaultHoliday($date) || isset($this->customHolidays[$dayMonth]);
    }

    /**
     * @throws InvalidCalendarTypeException
     * @throws InvalidEasterMethodException
     */
    public function getHolidayName(DateTime $date): string
    {
        $dayMonth = $date->format('d-m');
        if ($this->isDefaultHoliday($date)) {
            $holidayKey = $this->getDefaultHolidayName($date);

            return $this->translator->translate("holidays.$holidayKey");
        }

        return $this->customHolidays[$dayMonth][$this->locale] ?? '';
    }

    /**
     * @throws Exception
     */
    public function getNearestHoliday(DateTime $date): ?DateTime
    {
        $minDiff = PHP_INT_MAX;
        $nearestHolidayDate = null;

        $year = (int)$date->format('Y');
        $easterDate = $this->getEasterDate($year);

        $allHolidays = $this->getAllHolidays();
        $allHolidays[$easterDate->format('d-m')] = $this->translator->translate('Easter');
        $allHolidays = array_merge($allHolidays, $this->customHolidays);

        foreach ($allHolidays as $holidayDate => $holidayName) {
            $holidayDateTime = DateTime::createFromFormat('Y-d-m', $date->format('Y') . '-' . $holidayDate);
            if ($holidayDateTime === false) {
                throw new InvalidDateException($holidayDate);
            }

            $diff = abs($holidayDateTime->getTimestamp() - $date->getTimestamp());
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $nearestHolidayDate = $holidayDateTime;
            }
        }

        return $nearestHolidayDate;
    }

    public function getAllHolidays(): array
    {
        $defaultHolidays = $this->loadDefaultHolidays();
        return array_merge($defaultHolidays, $this->customHolidays);
    }

    /**
     * @throws InvalidEasterMethodException
     * @throws InvalidCalendarTypeException
     */
    private function isDefaultHoliday(DateTime $date): bool
    {
        $year = (int)$date->format('Y');
        $easterDate = $this->getEasterDate($year);

        $defaultHolidays = $this->defaultHolidays;
        if ($easterDate !== null) {
            $defaultHolidays[$easterDate->format('d-m')] = true;
        }

        $dateString = $date->format('d-m');

        return isset($defaultHolidays[$dateString]);
    }

    /**
     * @throws InvalidCalendarTypeException
     * @throws InvalidEasterMethodException
     */
    private function getDefaultHolidayName(DateTime $date): string
    {
        $year = (int)$date->format('Y');
        $easterDate = $this->getEasterDate($year);

        $defaultHolidays = $this->defaultHolidays;
        if ($easterDate !== null) {
            $defaultHolidays[$easterDate->format('d-m')] = $this->translator->translate('Easter');
        }

        $dateString = $date->format('d-m');

        return $defaultHolidays[$dateString] ?? '';
    }

    private function getCatholicEasterDate(int $year): DateTime
    {
        $a = $year % 19;
        $b = (int)($year / 100);
        $c = $year % 100;
        $d = (int)($b / 4);
        $e = $b % 4;
        $f = (int)(($b + 8) / 25);
        $g = (int)(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = (int)($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = (int)(($a + 11 * $h + 22 * $l) / 451);
        $month = (int)(($h + $l - 7 * $m + 114) / 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return new DateTime("{$year}-{$month}-{$day}");
    }

    private function getOrthodoxEasterDate(int $year): DateTime
    {
        $a = $year % 4;
        $b = $year % 7;
        $c = $year % 19;
        $d = (19 * $c + 15) % 30;
        $e = (2 * $a + 4 * $b - $d + 34) % 7;
        $month = (int)(($d + $e + 114) / 31);
        $day = (($d + $e + 114) % 31) + 1;

        // Adjust for the Julian calendar
        $julianEaster = new DateTime("{$year}-{$month}-{$day}");
        $julianEaster->add(new DateInterval('P13D'));

        return $julianEaster;
    }

    /**
     * @throws InvalidCalendarTypeException
     * @throws InvalidEasterMethodException
     */
    private function getEasterDate(int $year): ?DateTime
    {
        if ($this->easterMethod === null) {
            return match ($this->calendarType) {
                CalendarType::ORTHODOX => $this->getOrthodoxEasterDate($year),
                CalendarType::CATHOLIC => $this->getCatholicEasterDate($year),
                default => throw new InvalidCalendarTypeException('Nonexistent calendar')
            };
        }

        if ($this->easterMethod === 'none') {
            return null;
        }

        if (is_callable($this->easterMethod)) {
            return call_user_func($this->easterMethod, $year);
        }

        throw new InvalidEasterMethodException();
    }

    private function loadDefaultHolidays(): array
    {
        if (FrameworkDetector::isLaravel()) {
            return config('semantic-date.holidays', []);
        }

        $configPath = __DIR__ . '/../config/semantic-date.php';
        if (file_exists($configPath)) {
            $config = include $configPath;

            return $config['holidays'] ?? [];
        }

        return [];
    }
}
