<?php

use Haltsir\SemanticDate\DateConditions\WeeksAgo;
use Haltsir\SemanticDate\Translator;

beforeEach(function () {
    $translator = new Translator();
    $this->weeksAgo = new WeeksAgo($translator);
});

it('matches when the interval is at least 14 days and not more than a month ago', function () {
    $date = new DateTime('2023-03-15');
    $interval = new DateInterval('P20D');

    $result = $this->weeksAgo->match($date, $interval);

    expect($result)->toBe(true);
});

it('does not match when the interval is less than 14 days', function () {
    $date = new DateTime('2023-03-22');
    $interval = new DateInterval('P7D');

    $result = $this->weeksAgo->match($date, $interval);

    expect($result)->toBe(false);
});

it('does not match when the date is more than a month ago', function () {
    $date = new DateTime('2023-02-01');
    $interval = new DateInterval('P2M');

    $result = $this->weeksAgo->match($date, $interval);

    expect($result)->toBe(false);
});

it('returns the correct semantic representation for 2 weeks ago', function () {
    $date = new DateTime('2023-03-15');
    $interval = new DateInterval('P14D');

    $semanticRepresentation = $this->weeksAgo->getSemanticRepresentation($date, $interval);

    expect($semanticRepresentation)->toBe('2 weeks ago');
});

it('returns the correct semantic representation for 3 weeks ago', function () {
    $date = new DateTime('2023-03-08');
    $interval = new DateInterval('P21D');

    $semanticRepresentation = $this->weeksAgo->getSemanticRepresentation($date, $interval);

    expect($semanticRepresentation)->toBe('3 weeks ago');
});
