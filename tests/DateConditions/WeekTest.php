<?php

use Haltsir\SemanticDate\Translator;

$dataProvider = function () {
    return [
        'Monday' => [1, '2023-03-27', 'Monday'],
        'Tuesday' => [2, '2023-03-28', 'Tuesday'],
        'Wednesday' => [3, '2023-03-29', 'Wednesday'],
        'Thursday' => [4, '2023-03-30', 'Thursday'],
        'Friday' => [5, '2023-03-31', 'Friday'],
        'Saturday' => [6, '2023-04-01', 'Saturday'],
        'Sunday' => [7, '2023-04-02', 'Sunday'],
    ];
};

beforeEach(function () {
    $this->translator = new Translator();
});

it('matches when the interval is between 2 and 6 days and the date is a weekday', function ($weekdayNumber, $dateString, $weekdayClass) {
    // Arrange
    $date = new DateTime($dateString);
    $interval = new DateInterval('P3D');
    $fullClassName = "Haltsir\\SemanticDate\\DateConditions\\" . $weekdayClass;
    $weekdayCondition = new $fullClassName($this->translator);

    // Act
    $result = $weekdayCondition->match($date, $interval);

    // Assert
    expect($result)->toBe($date->format('N') == $weekdayNumber);
})->with($dataProvider);

it('does not match when the interval is less than 2 days', function ($weekdayNumber, $dateString, $weekdayClass) {
    // Arrange
    $date = new DateTime($dateString);
    $interval = new DateInterval('P1D');
    $fullClassName = "Haltsir\\SemanticDate\\DateConditions\\" . $weekdayClass;
    $weekdayCondition = new $fullClassName($this->translator);

    // Act
    $result = $weekdayCondition->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
})->with($dataProvider);

it('does not match when the interval is more than 6 days', function ($weekdayNumber, $dateString, $weekdayClass) {
    // Arrange
    $date = new DateTime($dateString);
    $interval = new DateInterval('P7D');
    $fullClassName = "Haltsir\\SemanticDate\\DateConditions\\" . $weekdayClass;
    $weekdayCondition = new $fullClassName($this->translator);

    // Act
    $result = $weekdayCondition->match($date, $interval);

    // Assert
    expect($result)->toBe(false);
})->with($dataProvider);

it('returns the correct semantic representation', function ($weekdayNumber, $dateString, $weekdayClass) {
    // Arrange
    $date = new DateTime($dateString);
    $interval = new DateInterval('P3D');
    $fullClassName = "Haltsir\\SemanticDate\\DateConditions\\" . $weekdayClass;
    $weekdayCondition = new $fullClassName($this->translator);

    // Act
    $semanticRepresentation = $weekdayCondition->getSemanticRepresentation($date, $interval);

    // Assert
    expect($semanticRepresentation)->toBe($weekdayClass);
})->with($dataProvider);
