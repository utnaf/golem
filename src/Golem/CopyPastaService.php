<?php

namespace Golem;

use Symfony\Component\Filesystem\Filesystem;
use Golem\Exception\GolemCopyPastaException;

final class CopyPastaService
{
    /** @var string */
    private $vendorDir;

    public function __construct(string $vendorDir)
    {
        $this->vendorDir = $vendorDir;
    }

    /** @throws \Golem\Exception\GolemCopyPastaException */
    public function moveFiles(): bool
    {
        $fs = new Filesystem;
        $destinationDir = $this->getDestinationDir();

        $dockerDir = $destinationDir.DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'docker';
        $makefile = $destinationDir.DIRECTORY_SEPARATOR.'Makefile';

        if ($fs->exists([$dockerDir, $makefile])) {
            throw new GolemCopyPastaException('Files alredy exists. Aborting.');
        }

        $fs->mirror($this->getResourcesDir(), $destinationDir);

        $dockerComposeFile = $dockerDir.DIRECTORY_SEPARATOR.'docker-compose.yml';
        $content = file_get_contents($dockerComposeFile);
        file_put_contents($dockerComposeFile, str_replace('{appname}', $this->getAppName(), $content));

        return true;
    }

    private function getResourcesDir(): string
    {
        $resourcesDir = implode(
            DIRECTORY_SEPARATOR,
            ['utnaf', 'golem', 'resources', 'build-plugin']
        );

        return $this->vendorDir
            .DIRECTORY_SEPARATOR
            .$resourcesDir
            .DIRECTORY_SEPARATOR;
    }

    private function getDestinationDir(): string
    {
        return dirname($this->vendorDir);
    }

    private function getAppName(): string
    {
        return preg_replace('/[^a-zA-Z]*/i', '', basename($this->getDestinationDir()));
    }
}