<?php

namespace Golem;

use Golem\Service\CopyPastaService;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class CopyPastaServiceTest extends TestCase
{
    /** @var array */
    private $baseDirectoryStructure;

    public function setUp()
    {
        $this->baseDirectoryStructure = [
            'vendor' => [
                'utnaf' => [
                    'golem' => [
                        'resources' => [
                            'build-plugin' => [
                                'docker-compose.yml' => 'dockerstuff',
                                'Makefile' => 'makefilestuff',
                                'build' => [
                                    'docker' => [
                                        'php' => [
                                            'Dockerfile' => 'dockerfilestuff'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /** @testdox Given a vendor directory when moveFiles is called then files are moved in the vendor parent directory */
    public function testFilesAreMoved()
    {
        $rootDir = vfsStream::setup('golem-test-dir', null, $this->baseDirectoryStructure);
        $copyPastaService = new CopyPastaService(vfsStream::url('golem-test-dir/vendor'));

        $copyPastaService->moveFiles();

        $this->assertTrue($rootDir->hasChild('Makefile'));
        $this->assertTrue($rootDir->hasChild('docker-compose.yml'));
        $this->assertTrue($rootDir->hasChild('build/docker/php/Dockerfile'));
    }

    /**
     * @testdox Givend an existent build directory when I call move files is called then an exception is thrown
     * @expectedException Golem\Exception\GolemCopyPastaException
     */
    public function testExceptionBuildDir()
    {
        $this->baseDirectoryStructure['build'] = [];
        $copyPastaService = new CopyPastaService(vfsStream::url('golem-test-dir/vendor'));

        $copyPastaService->moveFiles();
    }

    /**
     * @testdox Givend an existent Makefile when I call move files is called then an exception is thrown
     * @expectedException Golem\Exception\GolemCopyPastaException
     */
    public function testExceptionMakefile()
    {
        $this->baseDirectoryStructure['Makefile'] = 'makestuff';
        $copyPastaService = new CopyPastaService(vfsStream::url('golem-test-dir/vendor'));

        $copyPastaService->moveFiles();
    }

    /**
     * @testdox Givend an existent docker-compose.yml when I call move files is called then an exception is thrown
     * @expectedException Golem\Exception\GolemCopyPastaException
     */
    public function testExceptionDockerCompose()
    {
        $this->baseDirectoryStructure['docker-compose.yml'] = 'dockerstuff';
        $copyPastaService = new CopyPastaService(vfsStream::url('golem-test-dir/vendor'));

        $copyPastaService->moveFiles();
    }
}
