<?php

use Haltsir\SemanticDate\DateConverter;
use Haltsir\SemanticDate\Translator;

beforeEach(function() {
    $this->translator = new Translator('en');
});

it('should load translations for a given locale', function() {
    // Arrange

    // Act
    $translation = $this->translator->translate('holidays.christmas_day');

    // Assert
    expect($translation)->toEqual('Christmas Day');
});

it('should fallback to English translations if a locale is not found', function() {
    // Arrange
    $translator = new Translator('nonexistent-locale');

    // Act
    $translation = $translator->translate('holidays.christmas_day');

    // Assert
    expect($translation)->toEqual('Christmas Day');
});

it('should return the last key part if the key is not found', function() {
    // Arrange

    // Act
    $translation = $this->translator->translate('nonexistent.key');

    // Assert
    expect($translation)->toEqual('key');
});

it('should replace placeholders with provided values', function() {
    // Arrange
    $key = 'conditionals.around_holiday';
    $replace = ['holiday' => 'Christmas Day'];

    // Act
    $translation = $this->translator->translate($key, $replace);

    // Assert
    expect($translation)->toEqual('Around Christmas Day');
});

it('should translate holidays', function() {
    // Arrange

    // Act
    $converter = new DateConverter(locale: 'bg');
    $newYear = new DateTime('2023-01-01');

    // Assert
    expect($converter->convert($newYear))->toBe('Нова година');
});

it('should translate custom holidays', function() {
    $customHolidays = [
        '02-01' => [
            'en' => 'January 2nd',
            'bg' => 'Втори януари',
        ],
    ];

    $converter = new DateConverter(configuration: ['customHolidays' => $customHolidays], locale: 'bg');
    $testDate = new DateTime('2023-01-02');
    expect($converter->convert($testDate))->toBe('Втори януари');
});
