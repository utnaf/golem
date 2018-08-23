<?php

namespace Golem\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginInterface;
use Golem\CopyPastaService;

class Build implements PluginInterface, EventSubscriberInterface
{
    /** @var Composer */
    private $composer;

    /** @var IOInterface */
    private $io;

    /** @inheritdoc */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return [
            /** @see copyFiles() */
            PackageEvents::POST_PACKAGE_INSTALL => 'copyFiles',
        ];
    }

    public function copyFiles()
    {
        try {
            (new CopyPastaService($this->composer->getConfig()->get('vendor-dir')))->moveFiles();
        } catch (\Exception $e) {
            if ($e->getCode() !== CopyPastaService::EXCEPTION_CODE) {
                $this->io->write('<info>utnaf/golem: '.$e->getMessage().'</info>');
            }

            return;
        }

        $this->io->write('<info>utnaf/golem: build files copied succesfully.</info>');
    }

}