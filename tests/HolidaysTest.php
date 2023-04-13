<?php

use Haltsir\SemanticDate\CalendarType;
use Haltsir\SemanticDate\Holidays;
use Haltsir\SemanticDate\Translator;

beforeEach(function () {
    $this->customHolidays = [
        '01-01' => [
            'en' => 'Custom New Year\'s Day',
            'fr' => 'Nouvel An personnalisé',
        ],
    ];

    $this->translator = new Translator('en');
    $this->holidays = new Holidays($this->translator, 'en', $this->customHolidays, CalendarType::ORTHODOX);
});

it('should return true if the date is a holiday', function () {
    // Arrange
    $date = new DateTime('2023-01-01');

    // Act
    $isHoliday = $this->holidays->isHoliday($date);

    // Assert
    expect($isHoliday)->toBeTrue();
});

it('should return false if the date is not a holiday', function () {
    // Arrange
    $date = new DateTime('2023-01-02');

    // Act
    $isHoliday = $this->holidays->isHoliday($date);

    // Assert
    expect($isHoliday)->toBeFalse();
});

it('should return the holiday name if the date is a holiday', function () {
    // Arrange
    $date = new DateTime('2023-01-01');

    // Act
    $holidayName = $this->holidays->getHolidayName($date);

    // Assert
    expect($holidayName)->toEqual('New Year');
});

it('should return an empty string if the date is not a holiday', function () {
    // Arrange
    $date = new DateTime('2023-01-02');

    // Act
    $holidayName = $this->holidays->getHolidayName($date);

    // Assert
    expect($holidayName)->toEqual('');
});

it('should return the nearest holiday', function () {
    // Arrange
    $date = new DateTime('2023-01-02');

    // Act
    $nearestHoliday = $this->holidays->getNearestHoliday($date);

    // Assert
    $expectedNearestHoliday = new DateTime('2023-01-01');
    expect($nearestHoliday->format('Y-m-d'))->toEqual($expectedNearestHoliday->format('Y-m-d'));
});

it('should return all holidays including custom holidays', function () {
    // Arrange

    // Act
    $allHolidays = $this->holidays->getAllHolidays();

    // Assert
    expect($allHolidays)->toHaveKey('01-01');
    expect($allHolidays['01-01'])->toEqual([
        'en' => 'Custom New Year\'s Day',
        'fr' => 'Nouvel An personnalisé',
    ]);
});
