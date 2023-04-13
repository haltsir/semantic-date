<?php

namespace Haltsir\SemanticDate;

use Haltsir\SemanticDate\Frameworks\FrameworkDetector;

class Translator
{
    private string $locale;
    private array $translations;

    public function __construct(?string $locale = null)
    {
        $this->locale = $locale ?? 'en';
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $translationFile = __DIR__ . '/../lang/' . $this->locale . '/semantic-date.php';

        if (file_exists($translationFile)) {
            $this->translations = include $translationFile;
        } else {
            // Fallback to the English translation file
            $fallbackTranslationFile = __DIR__ . '/../lang/en/semantic-date.php';
            $this->translations = file_exists($fallbackTranslationFile) ? include $fallbackTranslationFile : [];
        }
    }

    public function translate(string $key, array $replace = []): string
    {
        if (FrameworkDetector::isLaravel()) {
            return __($key, $replace, $this->locale);
        }

        $keys = explode('.', $key);
        $translation = $this->translations;

        foreach ($keys as $keyPart) {
            if (!isset($translation[$keyPart])) {
                return end($keys);
            }
            $translation = $translation[$keyPart];
        }

        foreach ($replace as $search => $replaceValue) {
            $translation = str_replace(':' . $search, $replaceValue, $translation);
        }

        return $translation;
    }
}
