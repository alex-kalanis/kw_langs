<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_langs\Lang;
use kalanis\kw_paths\Path;


class LangTest extends CommonTestClass
{
    public function testBasic(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        Lang::init($path, 'foo');

        Lang::init($path, 'bar', new XLoader());
        Lang::load('baz');
        $this->assertEquals('bar', Lang::getLang());

        $this->assertEquals('pqr', Lang::get('def'));
        $this->assertEquals('ewq', Lang::get('ewq', 'lkj'));
        $this->assertEquals('vwx123', Lang::get('jkl', '123'));
        $this->assertEquals('asdf123', Lang::get('asdf%s', '123'));

        $this->assertInstanceOf('\kalanis\kw_langs\Loaders\PhpLoader', Lang::getLoader());
    }

    public function testLangsInPath(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        $path->setData(['path' => 'abcdef/ghijkl/mnopqrs.tuv']); // nope
        Lang::init($path, 'bar', new XLoader(), true);
        $this->assertEquals('bar', Lang::getLang());

        $path->setData(['path' => 'abc/def/ghijkl/mnopqrs.tuv']); // yep
        Lang::init($path, 'bar', new XLoader(), true);
        $this->assertEquals('abc', Lang::getLang());
    }

    public function testUnderscores(): void
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'php-src' . DIRECTORY_SEPARATOR . 'double.php';

        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        $path->setData(['lang' => 'cro']);
        Lang::init($path, 'bar', new XLoader());
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
    protected $lang = '';

    public function load(string $module, string $lang): array
    {
        $this->lang = $lang;
        return [
            'abc' => 'mno',
            'def' => 'pqr',
            'ghi' => 'stu',
            'jkl' => 'vwx%s',
        ];
    }
}
