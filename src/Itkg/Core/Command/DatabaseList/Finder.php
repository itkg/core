<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseList;

/**
 * Class Finder
 *
 * Find releases inside a directory
 *
 * @package Itkg\Core\Command\DatabaseList
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Finder implements FinderInterface
{
    /**
     * @var string
     */
    private $path = 'script/releases';

    /**
     * Find all releases
     *
     * @return array
     */
    public function findAll()
    {
        $files = array();

        foreach (new \DirectoryIterator($this->path) as $file) {
            if ($file->isDot()) {
                continue;
            }
            $files[] = $file->getFilename();
        }

        sort($files);

        return $files;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}
