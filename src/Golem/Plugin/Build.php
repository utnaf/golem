<?php

namespace Golem\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Golem\CopyPastaService;
use Symfony\Component\Filesystem\Exception\IOException;

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
            ScriptEvents::POST_INSTALL_CMD => 'copyFiles',
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'copyFiles',
        ];
    }

    public function copyFiles()
    {
        try {
            (new CopyPastaService($this->composer->getConfig()->get('vendor-dir')))->moveFiles();
        } catch (\Exception $e) {
            $this->io->write('<info>utnaf/golem: '. $e->getMessage() .'</info>');
            return;
        }

        $this->io->write('<info>utnaf/golem: build files copied succesfully.</info>');
    }

}