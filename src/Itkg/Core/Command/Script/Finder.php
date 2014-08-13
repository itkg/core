<?php

namespace Itkg\Core\Command\Script;

class Finder implements FinderInterface
{
    const DEFAULT_PATH = 'script/releases';

    /**
     * Get all scripts into a path
     * If a scriptName is defined, only scripts like $scriptName.php will be returned
     *
     * @param $release
     * @param null $path
     * @param null $scriptName
     * @return array
     */
    public function findAll($release, $path = null, $scriptName = null)
    {
        if (null == $path) {
            $path = self::DEFAULT_PATH;
        }

        $path = sprintf('%s/%s', $path, $release);

        $scripts = array();
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if (null != $scriptName && sprintf('%s.php', $scriptName) != $file->getFilename()) {
                continue;
            }
            
            $scripts[$file->getFilename()] = sprintf('%s/%s', $file->getPath(), $file->getFilename());
        }
        sort($scripts);

        return $scripts;
    }
}