<?php

use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\DateConditions\LastWeek;

beforeEach(function() {
    $translator = new Translator();
    $this->lastWeek = new LastWeek($translator);
});

it('matches when the date is within the last week', function () {
    // Arrange
    $date = new DateTime('2023-04-03');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastWeek->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the date is in the current week', function () {
    // Arrange
    $date = new DateTime('2023-04-10');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastWeek->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('does not match when the date is more than two weeks ago', function () {
    // Arrange
    $date = new DateTime('2023-03-27');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastWeek->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation for the last week', function () {
    // Arrange
    $date = new DateTime('2023-04-03');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastWeek->getSemanticRepresentation($date, $interval);

    // Assert
    expect($result)->toBe('Last week');
});

it('does not match when the date is in the future', function () {
    // Arrange
    $date = new DateTime('2023-04-17');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastWeek->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});
