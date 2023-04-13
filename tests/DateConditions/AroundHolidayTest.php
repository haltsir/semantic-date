<?php

use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\Holidays;
use Haltsir\SemanticDate\DateConditions\AroundHoliday;

beforeEach(function() {
    $this->translator = new Translator();
});

it('matches when date is within 3 days of Christmas', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-12-23');
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when date is more than 3 days away from Christmas', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-12-21');
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('matches when date is within 3 days of Easter', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-04-16'); // Easter Sunday: 2023-04-16
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when date is more than 3 days away from Easter', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-04-05');
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('matches when date is within 3 days of a custom holiday', function () {
    // Arrange
    $holidays = new Holidays($this->translator, 'en', [
        '13-04' => ['en' => 'Custom Holiday']
    ]);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-04-14');
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when date is more than 3 days away from a custom holiday', function () {
    // Arrange
    $holidays = new Holidays($this->translator, 'en', [
        '10-06' => ['en' => 'Custom Holiday']
    ]);
    $aroundHoliday = new AroundHoliday($this->translator, $holidays);
    $date = new DateTime('2023-06-15');
    $interval = new DateInterval('P1D');

    // Act
    $result = $aroundHoliday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});
