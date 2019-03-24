<?php

namespace Golem;

use Golem\Service\ReplaceStuffService;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

final class ReplaceStuffServiceTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $rootDir;

    public function setUp()
    {
        $this->rootDir = vfsStream::setup('golem-root-dir');
    }

    /**
     * @dataProvider stringProvider
     * @testdox Given a string with app placeholder the final string has the correct substitusions
     */
    public function testReplace($initialString, $expectedString)
    {
        $appName = 'Golem';
        $file = vfsStream::newFile('somefile.txt');
        $file->setContent($initialString);
        $this->rootDir->addChild($file);
        $replaceStuffService = new ReplaceStuffService();

        $result = $replaceStuffService->execute($file->url(), $appName);

        $this->assertTrue($result);
        $this->assertEquals($expectedString, file_get_contents($file->url()));
    }

    public function stringProvider()
    {
        return [
            ['{appname} rules', 'Golem rules'],
            ['{appname} rules, change my mind', 'Golem rules, change my mind'],
            ['Something {appname}ous is happening to your {appname} app', 'Something Golemous is happening to your Golem app']
        ];
    }
}
