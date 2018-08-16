<?php

namespace Golem;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

final class CopyPastaService {

    const EXCEPTION_CODE = 42;

    /** @var string */
    private $vendorDir;

    public function __construct(string $vendorDir) {
        $this->vendorDir = $vendorDir;
    }

    /** @throws \Symfony\Component\Filesystem\Exception\IOException */
    public function moveFiles(): bool {
        $fs = new Filesystem;
        $destinationDir = $this->getDestinationDir();

        $dockerDir = $destinationDir . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'docker';
        $makefile = $destinationDir . DIRECTORY_SEPARATOR . 'Makefile';

        if(!$fs->exists([$dockerDir, $makefile])) {
            throw new IOException('Files alredy exists. Aborting.', static::EXCEPTION_CODE);
        }

        $fs->mirror($this->getResourcesDir(), $destinationDir);

        $dockerComposeFile = $dockerDir . DIRECTORY_SEPARATOR . 'docker-compose.yml';
        $content = file_get_contents($dockerComposeFile);
        file_put_contents($dockerComposeFile, str_replace('{appname}', $this->getAppName(), $content));

        return true;
    }

    private function getResourcesDir(): string {
        $resourcesDir = implode(
            DIRECTORY_SEPARATOR,
            ['utnaf', 'golem', 'resources', 'build-plugin']
        );

        return $this->vendorDir
            . DIRECTORY_SEPARATOR
            . $resourcesDir
            . DIRECTORY_SEPARATOR;
    }

    private function getDestinationDir(): string {
        return dirname($this->vendorDir);
    }

    private function getAppName(): string {
        return preg_replace('/[^a-zA-Z]*/i', '', basename($this->getDestinationDir()));
    }
}