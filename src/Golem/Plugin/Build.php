<?php

namespace Golem\Plugin;

use Composer\Composer;
use Composer\DependencyResolver\GenericRule;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Golem\Service\CopyPastaService;
use Golem\Service\ReplaceStuffService;

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

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            /** @see copyFiles() */
            PackageEvents::POST_PACKAGE_INSTALL => 'copyFiles',
        ];
    }

    public function copyFiles(PackageEvent $event)
    {
        /** @var GenericRule $reason */
        $reason = $event->getOperation()->getReason();
        $isMe = $reason->getJob()['packageName'] === 'utnaf/golem';

        if(!$isMe) {
            return;
        }

        try {
            (new CopyPastaService(
                new ReplaceStuffService(),
                $this->composer->getConfig()->get('vendor-dir'))
            )->moveFiles();
        } catch (\Exception $e) {
            $this->io->write('<comment>' . $e->getMessage() . '</comment>');
            return;
        }

        $this->io->write('<info>build files copied succesfully.</info>');
    }

}
