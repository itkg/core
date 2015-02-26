<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class YamlLoader
 * @package Itkg\Core
 */
class YamlLoader extends FileLoader
{
    /**
     * Yaml parser instance
     * @var YamlParser
     */
    private $yamlParser;

    /**
     * Constructor.
     *
     * @param FileLocatorInterface $locator    A FileLocatorInterface instance
     * @param YamlParser           $yamlParser A yamlParser instance
     */
    public function __construct(FileLocatorInterface $locator, YamlParser $yamlParser)
    {
        parent::__construct($locator);
        $this->yamlParser = $yamlParser;
    }

    /**
     * Load yaml file
     *
     * @param string $file
     * @param string $type
     *
     * @return mixed
     */
    public function load($file, $type = null)
    {
        return (array) $this->yamlParser->parse(file_get_contents($file));
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
