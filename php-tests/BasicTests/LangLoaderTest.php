<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_langs\Interfaces\ILang;
use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_langs\Lang;
use kalanis\kw_langs\LangException;
use kalanis\kw_langs\Loaders\ClassLoader;
use kalanis\kw_langs\Loaders\MultiLoader;
use kalanis\kw_langs\Loaders\PhpLoader;
use kalanis\kw_langs\Support;
use kalanis\kw_paths\Path;
use kalanis\kw_paths\PathsException;
use kalanis\kw_routed_paths\RoutedPath;
use kalanis\kw_routed_paths\Sources as routeSource;


class LangLoaderTest extends CommonTestClass
{
    /**
     * @throws LangException
     */
    public function testGetVirtualFile(): void
    {
        Lang::init(new XYLoader(), 'hrk');
        Lang::load('dummy');
        $this->assertEquals('abcmnodefpqrghistujklvwx%syz0123%s456', Lang::get('wtf'));
    }

    /**
     * @throws LangException
     * @throws PathsException
     */
    public function testGetRealFile(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        $routed = new RoutedPath(new routeSource\Arrays(['lang' => 'fra']));
        Lang::init(new PhpLoader($path, $routed), Support::fillFromPaths($routed, 'hrk', true));
        Lang::load('dummy');
        $this->assertEquals('Alors quoi?', Lang::get('dashboard.page'));
    }

    /**
     * @throws LangException
     * @throws PathsException
     */
    public function testGetNoFile(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        $routed = new RoutedPath(new routeSource\Arrays(['lang' => 'fra']));
        Lang::init(new PhpLoader($path, $routed), Support::fillFromPaths($routed, 'hrk', true));
        Lang::load('unknown');
        $this->assertEquals('some.page', Lang::get('some.page'));
    }

    /**
     * @throws LangException
     */
    public function testGetNoTranslate(): void
    {
        Lang::init(new XYLoader(), 'hrk');
        Lang::load('unknown');
        $this->assertEquals('**really-not-existing', Lang::get('**really-not-existing'));
    }

    /**
     * @throws PathsException
     */
    public function testSupport(): void
    {
        $routed1 = new RoutedPath(new routeSource\Arrays(['lang' => 'hrk']));
        $this->assertEquals('hrk', Support::fillFromPaths($routed1, 'cas', true));

        $routed2 = new RoutedPath(new routeSource\Arrays(['lang' => '', 'path' => 'cas/off/nope']));
        $this->assertEquals('cas', Support::fillFromPaths($routed2, 'ign', true));
        $this->assertEquals('ign', Support::fillFromPaths($routed2, 'ign', false));
    }

    public function testSupportSetter(): void
    {
        $store = new \ArrayObject();
        $this->assertEquals('cas', Support::fillFromArray($store, 'cas'));
        Support::setToArray($store, '');
        $this->assertEquals('cas', Support::fillFromArray($store, 'cas'));
        Support::setToArray($store, 'moa');
        $this->assertEquals('moa', Support::fillFromArray($store, 'ign'));
    }

    /**
     * @throws LangException
     */
    public function testMultiLoader(): void
    {
        // init multi
        $lib = new MultiLoader();
        Lang::init($lib, 'hrk');

        // unknown now
        $lib->load('unknown', 'nop');

        // add some
        $lib->addLoader(new XYLoader());
        $this->assertEquals('**none-known', Lang::get('**none-known')); // not set
        Lang::load(XYLoader::class);
        $this->assertEquals('abc%smnodefpqrghistujklvwxyz%s0123456', Lang::get('srl')); // set
    }

    /**
     * @throws LangException
     */
    public function testClassLoader(): void
    {
        // init multi
        $lib = new ClassLoader();
        Lang::init($lib, 'hrk');

        // unknown now
        $lib->load('unknown', 'nop');

        // add some
        $lib->addClass(new XYLang());
        $this->assertEquals('**none-known', Lang::get('**none-known')); // not set
        Lang::load(XYLang::class);
        $this->assertEquals('43vwx%s', Lang::get('jkl78')); // set
    }
}


class XYLoader implements ILoader
{
    public function load(string $module, string $path = ''): array
    {
        return [
            'hrk' => [
                'wtf' => 'abcmnodefpqrghistujklvwx%syz0123%s456',
                'srl' => 'abc%smnodefpqrghistujklvwxyz%s0123456',
            ],
        ];
    }
}


class XYLang implements ILang
{
    public function setLang(string $lang): ILang
    {
        // nothing need
        return $this;
    }

    public function getTranslations(): array
    {
        return [
            'abc12' => '09mno',
            'jkl78' => '43vwx%s',
        ];
    }
}
