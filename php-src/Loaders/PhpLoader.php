<?php

namespace kalanis\kw_langs\Loaders;


use kalanis\kw_langs\Exception;
use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_paths\Interfaces\IPaths;
use kalanis\kw_paths\Path;


/**
 * Class PhpLoader
 * @package kalanis\kw_langs
 * Load config data from defined source
 * Contains personalized autoloader for configs!
 * @codeCoverageIgnore because internal autoloading
 */
class PhpLoader implements ILoader
{
    /** @var string[] */
    protected $pathMasks = [
        '%2$s%1$s%5$s%1$s%6$s%1$s%7$s%1$s%8$s%9$s', # all modules, translations in sub dir separated
        '%2$s%1$s%5$s%1$s%6$s%1$s%7$s%1$s%7$s%9$s', # all modules, translations as single file in sub dir
        '%2$s%1$s%5$s%1$s%6$s%1$s%7$s%8$s', # all modules, translations separated
        '%2$s%1$s%5$s%1$s%6$s%1$s%7$s%9$s', # all modules, translations in single file
        '%2$s%1$s%3$s%1$s%4$s%1$s%7$s%1$s%8$s%9$s', # custom user translations separated
        '%2$s%1$s%3$s%1$s%4$s%1$s%7$s%1$s%7$s%9$s', # custom user translation as single file
        '%2$s%1$s%7$s%1$s%8$s%9$s', # in lang root translations separated
        '%2$s%1$s%7$s%1$s%7$s%9$s', # in lang root as single file
    ];

    /** @var null|Path */
    protected $pathLib = null;

    public function setPathLib(?Path $pathLib): void
    {
        $this->pathLib = $pathLib;
    }

    public function load(string $module, string $lang): array
    {
        $path = $this->contentPath($module, $lang);
        return (!empty($path)) ? $this->includedLang($path, $lang) : [];
    }

    /**
     * @param string $module
     * @param string $lang
     * @return string|null
     * @throws Exception
     */
    public function contentPath(string $module, string $lang): ?string
    {
        if (empty($this->pathLib)) {
            throw new Exception('Need to set Path library first!');
        }
        $basicLookupDir = $this->pathLib->getDocumentRoot() . $this->pathLib->getPathToSystemRoot();
        foreach ($this->pathMasks as $pathMask) {
            $path = realpath(sprintf( $pathMask,
                DIRECTORY_SEPARATOR, $basicLookupDir,
                IPaths::DIR_USER, $this->pathLib->getUser(),
                IPaths::DIR_MODULE, $module,
                IPaths::DIR_LANG, $lang, IPaths::EXT
            ));
            if ($path) {
                return $path;
            }
        }
        return null;
    }

    protected function includedLang(string $path, string $which): array
    {
        $lang = [];
        include_once ($path);
        return (isset($lang[$which]) && is_array($lang[$which])) ? $lang[$which] : (array)$lang;
    }
}