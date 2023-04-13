<?php


use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\Holidays;
use Haltsir\SemanticDate\DateConditions\Holiday;

beforeEach(function() {
    $this->translator = new Translator();
});

it('matches when the date is a predefined holiday', function() {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-12-25'); // Christmas

    // Act
    $result = $holiday->match($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe(true);
});

it('matches when the date is a custom holiday', function() {
    // Arrange
    $holidays = new Holidays($this->translator, 'en', [
        '13-04' => ['en' => 'Custom Holiday']
    ]);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-04-13'); // Custom Holiday

    // Act
    $result = $holiday->match($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the date is not a holiday', function() {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-11-07'); // Non-holiday

    // Act
    $result = $holiday->match($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation for a predefined holiday', function() {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-12-25'); // Christmas

    // Act
    $result = $holiday->getSemanticRepresentation($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe('Christmas Day');
});

it('returns the correct semantic representation for a custom holiday', function() {
    // Arrange
    $holidays = new Holidays($this->translator, 'en', [
        '13-04' => ['en' => 'Custom Holiday']
    ]);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-04-13'); // Custom Holiday

    // Act
    $result = $holiday->getSemanticRepresentation($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe('Custom Holiday');
});

it('throws an exception for an invalid calendar type', function() {
    // Arrange
    $holidays = new Holidays($this->translator, calendarType: '');
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-11-07'); // Non-holiday

    // Act & Assert
    expect(fn() => $holiday->getSemanticRepresentation($date, new DateInterval('P1D')))
        ->toThrow(Haltsir\SemanticDate\Exceptions\InvalidCalendarTypeException::class);
});

it('matches when the date is Easter', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-04-16'); // Easter Sunday

    // Act
    $result = $holiday->match($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the date is not Easter', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-04-15'); // Day before Easter Sunday

    // Act
    $result = $holiday->match($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation for Easter', function () {
    // Arrange
    $holidays = new Holidays($this->translator);
    $holiday = new Holiday($this->translator, $holidays);
    $date = new DateTime('2023-04-16'); // Easter Sunday

    // Act
    $result = $holiday->getSemanticRepresentation($date, new DateInterval('P1D'));

    // Assert
    expect($result)->toBe('Easter');
});
