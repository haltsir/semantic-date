<?php

use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\DateConditions\LastMonth;

beforeEach(function() {
    $translator = new Translator();
    $this->lastMonth = new LastMonth($translator);
});

it('matches when the date is within the last month', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastMonth->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the date is in the current month', function () {
    // Arrange
    $date = new DateTime('2023-04-01');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastMonth->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('does not match when the date is more than two months ago', function () {
    // Arrange
    $date = new DateTime('2023-02-01');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastMonth->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation for the last month', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastMonth->getSemanticRepresentation($date, $interval);

    // Assert
    expect($result)->toBe('Last month');
});

it('does not match when the date is in the future', function () {
    // Arrange
    $date = new DateTime('2023-04-15');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastMonth->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});
