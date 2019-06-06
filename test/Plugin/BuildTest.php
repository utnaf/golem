<?php

namespace Golem\Plugin;

use Composer\Composer;
use Composer\Config;
use Composer\DependencyResolver\GenericRule;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Golem\FilesystemTestCase;
use Prophecy\Prophecy\ObjectProphecy;

final class BuildTest extends FilesystemTestCase
{
    /** @testdox Given a correct directory structure when build is called then the plugin is installed correctly */
    public function testCopyIsCorrect()
    {
        $rootDir = $this->getRootDir();

        /** @var ObjectProphecy|Config $composerConfig */
        $composerConfig = $this->prophesize(Config::class);
        $composerConfig->get('vendor-dir')->willReturn($rootDir->getChild('vendor')->url());

        /** @var ObjectProphecy|Composer $composer */
        $composer = $this->prophesize(Composer::class);
        $composer->getConfig()->willReturn($composerConfig->reveal());

        /** @var ObjectProphecy|IOInterface $io */
        $io = $this->prophesize(IOInterface::class);

        $buildPlugin = new Build();
        $buildPlugin->activate($composer->reveal(), $io->reveal());

        $buildPlugin->copyFiles($this->mockPackageEvent());

        $io->write('<info>utnaf/golem: build files copied succesfully.</info>')->shouldHaveBeenCalledOnce();

        $this->assertTrue($rootDir->hasChild('build/docker/php/Dockerfile'));
        $this->assertTrue($rootDir->hasChild('Makefile'));
        $this->assertTrue($rootDir->hasChild('docker-compose.yml'));
        $this->assertEquals($this->getCompiledPhpDockerfile(), file_get_contents($rootDir->getChild('build/docker/php/Dockerfile')->url()));
        $this->assertEquals($this->getCompiledMakefile(), file_get_contents($rootDir->getChild('Makefile')->url()));
        $this->assertEquals($this->getCompliledDockerCompose(), file_get_contents($rootDir->getChild('docker-compose.yml')->url()));
    }

    private function mockPackageEvent()
    {
        /** @var ObjectProphecy|GenericRule $reason */
        $reason = $this->prophesize(GenericRule::class);
        $reason->getJob()->willReturn([
            'packageName' => 'utnaf/golem'
        ]);

        $operation = $this->prophesize(OperationInterface::class);
        $operation->getReason()->willReturn($reason->reveal());

        /** @var ObjectProphecy|PackageEvent $packageEvent */
        $packageEvent = $this->prophesize(PackageEvent::class);
        $packageEvent->getOperation()->willReturn($operation->reveal());

        return $packageEvent->reveal();
    }
}
