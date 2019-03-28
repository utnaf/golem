<?php

namespace Golem\Service;

use Golem\FilesystemTestCase;
use org\bovigo\vfs\vfsStream;

final class CopyPastaServiceTest extends FilesystemTestCase
{

    /** @testdox Given a vendor directory when moveFiles is called then files are moved in the vendor parent directory */
    public function testFilesAreMoved()
    {
        $rootDir = $this->getRootDir();
        $copyPastaService = new CopyPastaService(new ReplaceStuffService(), vfsStream::url('golem-test-app/vendor'));

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
        $rootDir = $this->getRootDir(['build' => ['docker' => ['somefile' => 'somefilecontent']]]);

        $copyPastaService = new CopyPastaService(new ReplaceStuffService(), $rootDir->getChild('vendor')->url());

        $copyPastaService->moveFiles();
    }

    /**
     * @testdox Givend an existent Makefile when I call move files is called then an exception is thrown
     * @expectedException Golem\Exception\GolemCopyPastaException
     */
    public function testExceptionMakefile()
    {
        $rootDir = $this->getRootDir(['Makefile' => 'makefilestuff']);

        $copyPastaService = new CopyPastaService(new ReplaceStuffService(), $rootDir->getChild('vendor')->url());

        $copyPastaService->moveFiles();
    }

    /**
     * @testdox Givend an existent docker-compose.yml when I call move files is called then an exception is thrown
     * @expectedException Golem\Exception\GolemCopyPastaException
     */
    public function testExceptionDockerCompose()
    {
        $rootDir = $this->getRootDir(['docker-compose.yml' => 'dockerstuff']);
        $copyPastaService = new CopyPastaService(new ReplaceStuffService(), $rootDir->getChild('vendor')->url());

        $copyPastaService->moveFiles();
    }
}
