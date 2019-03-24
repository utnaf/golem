<?php

namespace Golem\Service;

use Symfony\Component\Filesystem\Filesystem;
use Golem\Exception\GolemCopyPastaException;

final class CopyPastaService
{
    /** @var string */
    private $vendorDir;

    /**
     * @param string $vendorDir
     */
    public function __construct($vendorDir)
    {
        $this->vendorDir = $vendorDir;
    }

    /**
     * @return bool
     * @throws \Golem\Exception\GolemCopyPastaException
     */
    public function moveFiles()
    {
        $fs = new Filesystem;
        $destinationDir = $this->getDestinationDir();

        $dockerDir = $destinationDir.DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'docker';
        $makefile = $destinationDir.DIRECTORY_SEPARATOR.'Makefile';
        $dockerComposeFile = $destinationDir.DIRECTORY_SEPARATOR.'docker-compose.yml';

        if ($fs->exists([$dockerDir, $makefile])) {
            throw new GolemCopyPastaException('Files alredy exists. Aborting.');
        }

        $fs->mirror($this->getResourcesDir(), $destinationDir);

        $this->replaceAppNameInFile($dockerComposeFile);
        $this->replaceAppNameInFile($makefile);
    }

    /**
     * @param string $filename
     */
    private function replaceAppNameInFile($filename)
    {
        $content = file_get_contents($filename);
        file_put_contents($filename, str_replace('{appname}', $this->getAppName(), $content));
    }

    /**
     * @return string
     */
    private function getResourcesDir()
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

    /**
     * @return string
     */
    private function getDestinationDir()
    {
        return dirname($this->vendorDir);
    }

    /**
     * @return string
     */
    private function getAppName()
    {
        return preg_replace('/[^a-zA-Z]*/i', '', basename($this->getDestinationDir()));
    }
}
