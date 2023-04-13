<?php

use Haltsir\SemanticDate\DateConditions\YearsAgo;
use Haltsir\SemanticDate\Translator;

beforeEach(function () {
    $translator = new Translator();
    $this->yearsAgo = new YearsAgo($translator);
});

it('matches when the interval is more than 730 days', function () {
    $date = new DateTime('2021-03-01');
    $interval = new DateInterval('P731D');

    $result = $this->yearsAgo->match($date, $interval);

    expect($result)->toBe(true);
});

it('does not match when the interval is less than or equal to 730 days', function () {
    $date = new DateTime('2021-03-01');
    $interval = new DateInterval('P730D');

    $result = $this->yearsAgo->match($date, $interval);

    expect($result)->toBe(false);
});

it('returns the correct semantic representation for 2 years ago', function () {
    $date = new DateTime('2021-03-01');
    $interval = new DateInterval('P2Y');

    $semanticRepresentation = $this->yearsAgo->getSemanticRepresentation($date, $interval);

    expect($semanticRepresentation)->toBe('2 years ago');
});

it('returns the correct semantic representation for 3 years ago', function () {
    $date = new DateTime('2020-03-01');
    $interval = new DateInterval('P3Y');

    $semanticRepresentation = $this->yearsAgo->getSemanticRepresentation($date, $interval);

    expect($semanticRepresentation)->toBe('3 years ago');
});
