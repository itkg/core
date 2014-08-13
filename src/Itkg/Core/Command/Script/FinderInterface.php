<?php

namespace Itkg\Core\Command\Script;

interface FinderInterface
{
    /**
     * Get all scripts into a path
     * If a scriptName is defined, only scripts like $scriptName.php will be returned
     *
     * @param $release
     * @param null $path
     * @param null $scriptName
     * @return array
     */
    public function findAll($release, $path = null, $scriptName = null);
} 