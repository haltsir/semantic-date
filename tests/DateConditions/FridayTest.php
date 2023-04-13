<?php

use Haltsir\SemanticDate\Translator;
use Haltsir\SemanticDate\DateConditions\Friday;

beforeEach(function() {
    $translator = new Translator();
    $this->friday = new Friday($translator);
});

it('matches when interval is 2 days and the date is a Friday', function () {
    // Arrange
    $date = new DateTime('2023-03-31'); // Friday
    $interval = $date->diff((clone $date)->modify('-2 days'));

    // Act
    $result = $this->friday->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('matches when interval is 6 days and the date is a Friday', function () {
    // Arrange
    $date = new DateTime('2023-03-31'); // Friday
    $interval = $date->diff((clone $date)->modify('-6 days'));

    // Act
    $result = $this->friday->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when interval is 1 day and the date is a Friday', function () {
    // Arrange
    $date = new DateTime('2023-03-31'); // Friday
    $interval = $date->diff((clone $date)->modify('-1 day'));

    // Act
    $result = $this->friday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('does not match when interval is 7 days and the date is a Friday', function () {
    // Arrange
    $date = new DateTime('2023-03-31'); // Friday
    $interval = $date->diff((clone $date)->modify('-7 days'));

    // Act
    $result = $this->friday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('does not match when interval is 6 days and the date is not a Friday', function () {
    // Arrange
    $date = new DateTime('2023-03-30'); // Thursday
    $interval = $date->diff((clone $date)->modify('-6 days'));

    // Act
    $result = $this->friday->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('has correct semantic representation', function () {
    // Arrange
    $date = new DateTime('2023-03-31'); // Friday
    $interval = $date->diff((clone $date)->modify('-6 days'));

    // Act
    $result = $this->friday->getSemanticRepresentation($date, $interval);

    // Assert
    expect($result)->toBe('Friday');
});
