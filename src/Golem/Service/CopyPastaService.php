<?php

namespace Golem\Service;

use Golem\Exception\GolemCopyPastaException;

final class CopyPastaService
{
    /** @var string */
    private $vendorDir;

    /** @var ReplaceStuffService */
    private $replaceStuffService;

    /**
     * @param ReplaceStuffService $replaceStuffService
     * @param string $vendorDir
     */
    public function __construct($replaceStuffService, $vendorDir)
    {
        $this->replaceStuffService = $replaceStuffService;
        $this->vendorDir = $vendorDir;
    }

    /** @throws \Golem\Exception\GolemCopyPastaException */
    public function moveFiles()
    {
        $destinationDir = $this->getDestinationDir();

        $dockerDir = $destinationDir . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'docker';
        $makefile = $destinationDir . DIRECTORY_SEPARATOR . 'Makefile';
        $dockerComposeFile = $destinationDir . DIRECTORY_SEPARATOR . 'docker-compose.yml';

        if (file_exists($dockerDir)
            || file_exists($makefile)
            || file_exists($dockerComposeFile)
        ) {
            throw new GolemCopyPastaException('Files alredy exists. Aborting.');
        }

        $this->mirror($this->getResourcesDir(), $destinationDir);

        $appName = $this->getAppName();
        $this->replaceStuffService->execute($makefile, $appName);
        $this->replaceStuffService->execute($dockerComposeFile, $appName);
    }

    private function mirror($fromDir, $toDir)
    {
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($fromDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                mkdir($toDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $toDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    /** @return string */
    private function getResourcesDir()
    {
        $resourcesDir = implode(
            DIRECTORY_SEPARATOR,
            ['utnaf', 'golem', 'resources', 'build-plugin']
        );

        return $this->vendorDir
            . DIRECTORY_SEPARATOR
            . $resourcesDir
            . DIRECTORY_SEPARATOR;
    }

    /** @return string */
    private function getDestinationDir()
    {
        return dirname($this->vendorDir);
    }

    /** @return string */
    private function getAppName()
    {
        return preg_replace('/[^a-zA-Z]*/i', '', basename($this->getDestinationDir()));
    }
}
