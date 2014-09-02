<?php

namespace Itkg\Core;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\FileLocatorInterface;

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
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}

