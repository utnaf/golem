<?php

namespace Golem\Service;

final class ReplaceStuffService
{
    /**
     * @param string $filename
     * @param string $appName
     * @return bool
     */
    public function execute($filename, $appName)
    {
        $fileContent = file_get_contents($filename);
        return file_put_contents($filename, str_replace('{appname}', $appName, $fileContent)) !== false;
    }
}
