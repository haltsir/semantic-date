# Semantic Date

Semantic Date is a PHP library that provides an easy way to convert dates into human-readable, semantic expressions. The library is framework-agnostic but includes specific instructions for integration with the Laravel framework. The package supports two calendar types: Orthodox and Catholic.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Integration with Laravel](#integration-with-laravel)
- [Contributing](#contributing)

## Requirements

- PHP >= 8.0
- Composer

## Installation

To install the Semantic Date library, use the following Composer command:

```bash
composer require haltsir/semantic-date
```

## Usage

To use the Semantic Date library, first create an instance of the DateConverter class, then call the convert method with the date you want to convert:

```php
use Haltsir\SemanticDate\DateConverter;

$converter = new DateConverter();
$semanticDate = $converter->convert(new DateTime('your date here'));
```

You can also pass additional options to the DateConverter constructor, such as custom holidays, excluded date conditions, and a calendar type. The package supports the Orthodox (default) and Catholic calendars. To use a specific calendar, set the calendarType option.

By default, the locale is set to 'en' (English). The library uses the given locale to determine the translations for holidays and other date-related expressions. You can change the default locale by passing it as the second parameter to the DateConverter constructor.

### Laravel

To set up on Laravel, follow these steps:
1. Add the service provider to the providers array:
```php
'providers' => [
    // ...
    Haltsir\SemanticDate\SemanticDateServiceProvider::class,
],
```
2. Add the facade to the aliases array:
```php
'aliases' => [
    // ...
    'SemanticDate' => Haltsir\SemanticDate\Facades\SemanticDateFacade::class,
],
```

Now you can use the SemanticDate facade in your Laravel application:
```php
use SemanticDate;

$date = new DateTime('2023-04-10');
$convertedDate = SemanticDate::convert($date);
```

## Examples

Here are some examples of how to use the Semantic Date library:

### Basic usage
```php
use Haltsir\SemanticDate\DateConverter;

$converter = new DateConverter();
echo $converter->convert(new DateTime('today')); // Output: Today
echo $converter->convert(new DateTime('yesterday')); // Output: Yesterday
```

### Custom holidays
```php
use Haltsir\SemanticDate\DateConverter;

$configuration = [
    'customHolidays' => ['27-12' => 'Stefan Name Day']
];

$converter = new DateConverter($configuration);
echo $converter->convert(new DateTime('2023-12-27')); // Output: Stefan Name Day
```

### Excluding date conditions
```php
use Haltsir\SemanticDate\DateConverter;
use Haltsir\SemanticDate\DateConditions\Today;

$configuration = [
    'excludedDateConditions' => [Today::class]
];

$converter = new DateConverter($configuration);
echo $converter->convert(new DateTime('today')); // Output: the actual date instead of "Today"
```

### Changing calendar type and locale
```php
use Haltsir\SemanticDate\DateConverter;
use Haltsir\SemanticDate\CalendarType;

$configuration = [
    'calendarType' => CalendarType::CATHOLIC
];

$converter = new DateConverter($configuration, 'fr');
echo $converter->convert(new DateTime('2023-04-09')); // Output: Pâques (Catholic Easter in French)
```

### Custom Easter observation

You can also set the method to 'none'.

```php
use Haltsir\SemanticDate\DateConverter;
use Haltsir\SemanticDate\CalendarType;

$configuration = [
    'calendarType' => CalendarType::CUSTOM
];

$converter = new DateConverter(['calendarType' => CalendarType::CUSTOM, 'easterMethod' => 'MyClass::myMethod']);
```

## Integration with Laravel

To integrate the Semantic Date library with a Laravel application, follow these steps:

1. Publish the configuration and language files using the following Artisan command:
```bash
php artisan vendor:publish --provider="Haltsir\SemanticDate\SemanticDateServiceProvider"
```
2. Modify the published configuration file (config/semantic-date.php) to customize the library behavior, such as default holidays and other settings.
3. If you want to add your own custom translations, create a new translations file in your app's lang directory. For example, if you want to add custom translations for the English language, create a semantic-date.php file inside the resources/lang/en directory and add your translations there.
4. In your Laravel application, use the Semantic Date library as shown in the Usage and Examples sections. The library will automatically detect the locale set in your Laravel application's configuration (config/app.php). If you want to change the locale for a specific instance of the DateConverter class, pass the desired locale as the second parameter to the constructor:
```php
use Haltsir\SemanticDate\DateConverter;
use Haltsir\SemanticDate\CalendarType;

$configuration = [
    'calendarType' => CalendarType::CATHOLIC
];

$converter = new DateConverter($configuration, 'es');
echo $converter->convert(new DateTime('2023-04-16')); // Output: Pascua Ortodoxa (Orthodox Easter in Spanish)
```

## Contributing

Contributions to the Semantic Date library are welcome! To contribute, please follow these steps:

1. Fork the repository on GitHub.
2. Create a new branch for your changes.
3. Write extensive tests for your changes using the Pest testing framework.
4. Submit a pull request with your proposed changes or feature requests.

If you encounter any problems or need assistance, feel free to create an issue or reach out to the maintainers for help.
