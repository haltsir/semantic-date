<?php

use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\DateConditions\LastYear;

beforeEach(function() {
    $translator = new Translator();
    $this->lastYear = new LastYear($translator);
});

it('matches when the date is within the last year', function () {
    // Arrange
    $date = new DateTime('2021-05-15');
    $interval = (new DateTime('2022-05-15'))->diff($date);

    // Act
    $result = $this->lastYear->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the date is in the current year', function () {
    // Arrange
    $date = new DateTime('2023-01-15');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastYear->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('does not match when the date is more than two years ago', function () {
    // Arrange
    $date = new DateTime('2020-05-15');
    $interval = (new DateTime('2023-04-10'))->diff($date);

    // Act
    $result = $this->lastYear->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation for the last year', function () {
    // Arrange
    $date = new DateTime('2021-05-15');
    $interval = (new DateTime('2022-05-15'))->diff($date);

    // Act
    $result = $this->lastYear->getSemanticRepresentation($date, $interval);

    // Assert
    expect($result)->toBe('Last year');
});
