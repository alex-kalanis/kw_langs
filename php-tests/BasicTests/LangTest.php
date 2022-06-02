<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_langs\Interfaces\ILang;
use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_langs\Lang;
use kalanis\kw_langs\LangException;
use kalanis\kw_langs\Loaders\PhpLoader;
use kalanis\kw_langs\Support;
use kalanis\kw_paths\Path;


class LangTest extends CommonTestClass
{
    /**
     * @throws LangException
     */
    public function testBasic(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        Lang::init(new PhpLoader($path), 'foo');

        Lang::init(new XLoader(), 'bar');
        Lang::load('baz');
        $this->assertEquals('bar', Lang::getLang());

        $this->assertEquals('pqr', Lang::get('def'));
        $this->assertEquals('ewq', Lang::get('ewq', 'lkj'));
        $this->assertEquals('vwx123', Lang::get('jkl', '123'));
        $this->assertEquals('asdf123', Lang::get('asdf%s', '123'));
        $this->assertEquals('asdf%s', Lang::get('asdf%s'));
        $this->assertEquals('123%s456', Lang::get('yz0'));

        $this->assertInstanceOf('\kalanis\kw_langs\Interfaces\ILoader', Lang::getLoader());
    }

    public function testClass(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        Lang::init(new PhpLoader($path), Support::fillFromPaths($path, 'bar', false));
        Lang::loadClass(new XLang());
        $this->assertEquals('bar', Lang::getLang());

        $this->assertEquals('65stu', Lang::get('ghi56'));
        $this->assertEquals('ewq', Lang::get('ewq', 'lkj'));
        $this->assertEquals('43vwx123', Lang::get('jkl78', '123'));
        $this->assertEquals('asdf123', Lang::get('asdf%s', '123'));
    }

    public function testLangsInPath(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        $path->setData(['path' => 'abcdef/ghijkl/mnopqrs.tuv']); // nope
        Lang::init(new PhpLoader($path), Support::fillFromPaths($path, 'bar', true));
        $this->assertEquals('bar', Lang::getLang());

        $path->setData(['path' => 'abc/def/ghijkl/mnopqrs.tuv']); // yep
        Lang::init(new PhpLoader($path), Support::fillFromPaths($path, 'bar', true));
        $this->assertEquals('abc', Lang::getLang());
    }

    /**
     * @throws LangException
     */
    public function testUnderscores(): void
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'php-src' . DIRECTORY_SEPARATOR . 'double.php';

        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        $path->setData(['lang' => 'cro']);
        Lang::init(new PhpLoader($path), Support::fillFromPaths($path, 'bar', false));
        Lang::load('baz');

        $this->assertEquals('cro', Lang::getLang());
        $this->assertEquals('pqr', __('def'));
        $this->assertEquals('ewq', __('ewq', 'lkj'));
        $this->assertEquals('vwx123', __('jkl', '123'));
        $this->assertEquals('asdf123', __('asdf%s', '123'));
    }
}


class XLoader implements ILoader
{
    public function load(string $module, string $lang): array
    {
        return [
            'abc' => 'mno',
            'def' => 'pqr',
            'ghi' => 'stu',
            'jkl' => 'vwx%s',
            'yz0' => '123%s456',
        ];
    }
}


class XLang implements ILang
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
            'def34' => '87pqr',
            'ghi56' => '65stu',
            'jkl78' => '43vwx%s',
        ];
    }
}
