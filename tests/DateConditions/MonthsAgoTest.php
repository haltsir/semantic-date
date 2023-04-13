<?php

use Haltsir\SemanticDate\DateConditions\MonthsAgo;
use Haltsir\SemanticDate\Translator;

beforeEach(function () {
    $translator = new Translator();
    $this->condition = new MonthsAgo($translator);
});

it('matches dates within the last 12 months', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $today = new DateTime('2023-01-15');
    $interval = $today->diff($date);

    // Act
    $result = $this->condition->match($date, $interval);

    // Assert
    expect($result)->toBeTrue();
});

it('does not match dates more than 12 months ago', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $today = new DateTime('2024-04-15');
    $interval = $today->diff($date);

    // Act
    $result = $this->condition->match($date, $interval);

    // Assert
    expect($result)->toBeFalse();
});

it('does not match dates less than 1 month ago', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $today = new DateTime('2023-03-10');
    $interval = $today->diff($date);

    // Act
    $result = $this->condition->match($date, $interval);

    // Assert
    expect($result)->toBeFalse();
});

it('matches dates within 12 months but between different years', function () {
    // Arrange
    $date = new DateTime('2022-12-15');
    $today = new DateTime('2023-03-15');
    $interval = $today->diff($date);

    // Act
    $result = $this->condition->match($date, $interval);

    // Assert
    expect($result)->toBeTrue();
});

it('returns the correct semantic representation', function () {
    // Arrange
    $date = new DateTime('2023-03-15');
    $today = new DateTime('2023-10-15');
    $interval = $today->diff($date);

    // Act
    $result = $this->condition->getSemanticRepresentation($date, $interval);

    // Assert
    expect($result)->toBe('7 months ago');
});
