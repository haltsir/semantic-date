<?php

use Haltsir\SemanticDate\DateConditions\Today;
use Haltsir\SemanticDate\Translator;

beforeEach(function () {
    $this->translator = new Translator();
    $this->today = new Today($this->translator);
});

it('matches when the interval is 0 days', function () {
    // Arrange
    $date = new DateTime('2023-03-01');
    $now = new DateTime('2023-03-01');
    $interval = $now->diff($date);

    // Act
    $result = $this->today->match($date, $interval);

    // Assert
    expect($result)->toBe(true);
});

it('does not match when the interval is more than 0 days', function () {
    // Arrange
    $date = new DateTime('2023-03-01');
    $now = new DateTime('2023-03-02');
    $interval = $now->diff($date);

    // Act
    $result = $this->today->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
});

it('returns the correct semantic representation', function () {
    // Arrange
    $date = new DateTime('2023-03-01');
    $now = new DateTime('2023-03-01');
    $interval = $now->diff($date);

    // Act
    $semanticRepresentation = $this->today->getSemanticRepresentation($date, $interval);

    // Assert
    expect($semanticRepresentation)->toBe('Today');
});
