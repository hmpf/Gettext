<?php

namespace Gettext\Tests;

use Gettext\Translations;
use PHPUnit_Framework_TestCase;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    protected static $ext = [
        'Blade' => 'php',
        'Csv' => 'csv',
        'CsvDictionary' => 'csv',
        'Jed' => 'json',
        'JsCode' => 'js',
        'JsonDictionary' => 'json',
        'Json' => 'json',
        'Mo' => 'mo',
        'PhpArray' => 'php',
        'PhpCode' => 'php',
        'Po' => 'po',
        'Twig' => 'php',
        'Xliff' => 'xlf',
        'Yaml' => 'yml',
        'YamlDictionary' => 'yml',
    ];

    protected static function asset($file)
    {
        return './tests/assets/'.$file;
    }

    protected static function get($file, $format = null)
    {
        if ($format === null) {
            $format = basename($file);
        }

        $method = "from{$format}File";
        $file = static::asset($file.'.'.static::$ext[$format]);

        return Translations::$method($file);
    }

    protected function assertContent(Translations $translations, $file, $format = null)
    {
        if ($format === null) {
            $format = basename($file);
        }

        $method = "to{$format}String";
        $content = file_get_contents(static::asset($file.'.'.static::$ext[$format]));

        $this->assertSame($content, $translations->$method());
    }

    protected static function saveContent(Translations $translations, $file, $format = null)
    {
        if ($format === null) {
            $format = basename($file);
        }

        $method = "to{$format}String";
        $file = static::asset($file.'.'.static::$ext[$format]);

        file_put_contents($file, $translations->$method());
    }

    protected function runTestFormat($file, $countTranslations, $countHeaders = 8)
    {
        $format = basename($file);
        $method = "from{$format}File";

        $translations = Translations::$method(static::asset($file.'.'.static::$ext[$format]));

        $this->assertCount($countTranslations, $translations);
        $this->assertCount($countHeaders, $translations->getHeaders(), json_encode($translations->getHeaders(), JSON_PRETTY_PRINT));
        $this->assertContent($translations, $file);
    }
}
