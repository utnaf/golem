<?php

namespace Golem\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Golem\CopyPastaService;
use Symfony\Component\Filesystem\Exception\IOException;

class Build implements PluginInterface, EventSubscriberInterface {
    /** @var Composer */
    private $composer;

    /** @var IOInterface */
    private $io;

    /** @inheritdoc */
    public function activate(Composer $composer, IOInterface $io) {
        $this->composer = $composer;
        $this->io       = $io;
    }

    public static function getSubscribedEvents() {
        return [
            /** @see copyFiles() */
            ScriptEvents::POST_INSTALL_CMD => 'copyFiles',
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'copyFiles'
        ];
    }

    public function copyFiles() {
        try {
            (new CopyPastaService($this->composer->getConfig()->get('vendor-dir')))->moveFiles();
        } catch (IOException $e) {
        }
        catch (\Exception $e) {
            if($e instanceof IOException && $e->getCode() !== CopyPastaService::EXCEPTION_CODE) {
                // no need to print messages, this will happen everytime `composer install` is executed if the files
                // are already there
                return;
            }

            $this->io->write('<info>utnaf/golem: files already exists, override is not safe, skipped.</info>');
            return;
        }

        $this->io->write('<info>utnaf/golem: build files copied succesfully.</info>');
    }

}