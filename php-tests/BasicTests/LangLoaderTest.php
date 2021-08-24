<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_paths\Path;
use kalanis\kw_langs\Interfaces\ILoader;
use kalanis\kw_langs\Loaders\PhpLoader;
use kalanis\kw_langs\Lang;
use kalanis\kw_langs\LangException;


class LangLoaderTest extends CommonTestClass
{
    public function testLoaderException(): void
    {
        $loader = new PhpLoader();
        $this->expectException(LangException::class);
        $loader->load('dummy', 'file');
    }

    public function testGetVirtualFile(): void
    {
        $path = new Path();
        $path->setDocumentRoot('/tmp/none');
        $path->setData(['lang' => 'hrk']);
        Lang::init($path, 'hrk', new XYLoader());
        Lang::load('dummy');
        $this->assertEquals('abcmnodefpqrghistujklvwx%syz0123%s456', Lang::get('wtf'));
    }

    public function testGetRealFile(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        $path->setData(['lang' => 'fra']);
        Lang::init($path, 'fra');
        Lang::load('dummy');
        $this->assertEquals('Alors quoi?', Lang::get('dashboard.page'));
    }

    public function testGetNoFile(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        $path->setData(['lang' => 'hrk']);
        Lang::init($path, 'hrk');
        Lang::load('unknown');
        $this->assertEquals('**really-not-existing', Lang::get('**really-not-existing'));
    }
}


class XYLoader implements ILoader
{
    public function load(string $module, string $path = ''): array
    {
        return [
            'hrk' => [
                'wtf' => 'abcmnodefpqrghistujklvwx%syz0123%s456',
            ],
        ];
    }
}
