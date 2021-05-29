<?php

namespace kalanis\kw_langs;


use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_paths\Path;
use kalanis\kw_paths\Stuff;


/**
 * Class Lang
 * @package kalanis\kw_langs
 * Store translations through system runtime
 */
class Lang
{
    /** @var ILoader */
    protected static $loader = null;
    /** @var string[][] */
    protected static $translations = [];
    /** @var string */
    protected static $usedLang = '';

    public static function init(Path $path, string $defaultLang, ?ILoader $loader = null, bool $moreLangs = false): void
    {
        if (empty($loader)) {
            $loader = new Loaders\PhpLoader();
            $loader->setPathLib($path);
        }
        static::$usedLang = static::fillFromPaths($path, $defaultLang, $moreLangs);
        static::$loader = $loader;
    }

    protected static function fillFromPaths(Path $path, string $defaultLang, bool $moreLangs): string
    {
        if ($path->getLang()) {
            return $path->getLang();
        }
        if ($moreLangs && !empty($path->getPath())) {
            $trace = Stuff::pathToArray($path->getPath());
            $firstDir = reset($trace);
            $length = strlen($firstDir);
            if (1 < $length && 4 > $length) { // two-letter "en", three letter "eng"
                return $firstDir;
            }
        }
        return $defaultLang;
    }

    public static function load(string $module): void
    {
        static::$translations = array_merge(static::$translations, static::$loader->load($module, static::$usedLang));
    }

    public static function get(string $key, ...$pass): string
    {
        $content = (isset(static::$translations[$key])) ? static::$translations[$key] : $key ;
        return call_user_func_array('sprintf', array_merge([$content], $pass));
    }

    public static function getLang(): string
    {
        return static::$usedLang;
    }

    public static function getLoader(): ?ILoader
    {
        return static::$loader;
    }
}
