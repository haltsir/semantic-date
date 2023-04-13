<?php

use Haltsir\SemanticDate\DateConverter;

function createDateConverter(array $configuration = [], string $locale = 'en'): DateConverter
{
    return new DateConverter($configuration, $locale);
}

beforeEach(function () {
    $this->dateConverter = createDateConverter();
});

it('converts custom holiday', function () {
    $customHolidays = [
        '20-04' => ['en' => 'Custom Holiday'],
    ];
    $dateConverter = createDateConverter(['customHolidays' => $customHolidays]);
    $date = new DateTime('2023-04-20');

    $result = $dateConverter->convert($date);

    expect($result)->toBe('Custom Holiday');
});

it('excludes date conditions', function () {
    $excludedDateConditions = [
        Haltsir\SemanticDate\DateConditions\Today::class,
    ];
    $dateConverter = createDateConverter(['excludedDateConditions' => $excludedDateConditions]);
    $date = new DateTime('2023-03-27');

    $result = $dateConverter->convert($date);

    expect($result)->not->toBe('today');
});
