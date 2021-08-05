<?php

namespace kalanis\kw_langs\Interfaces;


use kalanis\kw_langs\LangException;


/**
 * Class ILoader
 * @package kalanis\kw_langs\Interfaces
 * Load translation data from defined source
 */
interface ILoader
{
    /**
     * @param string $module which module it will be looked for
     * @param string $lang which lang name will be looked for
     * @return string[] translations array
     * @throws LangException
     */
    public function load(string $module, string $lang): array;
}
