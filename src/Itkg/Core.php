<?php

namespace Itkg;

use Itkg\Exception\NotFoundException;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class Core
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class Core
{


    /**
     * Debug mode
     *
     * @var boolean
     */
    protected $isDebug;

    /**
     * Extension's list
     *
     * @var array
     */
    protected $extensions;

    /**
     * Cache file path
     *
     * @var string
     */
    protected $cacheFile;

    /**
     * DIC
     *
     * @var ContainerBuilder
     */
    protected static $container;

    /**
     * Runtime config for service
     *
     * @static
     * @var array
     */
    public static $config;

    /**
     * Constructor
     *
     * @param string  $cacheFile Path to cache file
     * @param boolean $isDebug   Debug mode
     */
    public function __construct($cacheFile, $isDebug = false)
    {
        $this->isDebug = $isDebug;
        $this->cacheFile = $cacheFile;
    }

    /**
     * Load container if it is not yet loaded
     *
     * @param array $config Override extension config
     */
    public function load($config = array())
    {
        if (!self::$container) {
            $containerConfigCache = new ConfigCache(
                $this->cacheFile,
                $this->isDebug
            );

            if (!$containerConfigCache->isFresh()) {
                self::$container = new ContainerBuilder();
                // Defaults compiler pass
                self::$container->addCompilerPass(new \Itkg\Core\DependencyInjection\Compiler\SubscriberCompilerPass());

                foreach ($this->getExtensions() as $extension) {
                    self::$container->registerExtension($extension);
                    self::$container->loadFromExtension($extension->getAlias(), $config);
                }

                self::$container->compile();

                $dumper = new PhpDumper(self::$container);
                $containerConfigCache->write(
                    $dumper->dump(array('class' => 'ItkgContainer')),
                    self::$container->getResources()
                );
                return;
            }
            include_once $this->cacheFile;
            self::$container = new \ItkgContainer();

        }
    }

    /**
     * Register an extension
     *
     * @param ExtensionInterface $extension Extension to register
     */
    public function registerExtension(ExtensionInterface $extension)
    {
        if (!$this->extensions) {
            $this->extensions = array(new Itkg\Core\DependencyInjection\ItkgCoreExtension());
        }

        $this->extensions[] = $extension;
    }

    /**
     * Get extension's list
     *
     * @return array Extension's list
     */
    public function getExtensions()
    {
        if (!$this->extensions) {
            $this->extensions = array();
        }

        return $this->extensions;
    }

    /**
     * Set extension's list
     *
     * @param array $extensions Extension's list
     */
    public function setExtensions(array $extensions = array())
    {
        $this->extensions = $extensions;
    }

    /**
     * Get container
     *
     * @return ContainerBuilder Container
     */
    public function getContainer()
    {
        return self::$container;
    }

    public function get($key)
    {
        return self::$container->get($key);
    }

    public function has($key)
    {
        return self::$container->has($key);
    }

    public function set($key, $service)
    {
        self::$container->set($key, $service);
    }

    /**
     * Set container
     *
     * @param ContainerBuilder $container [description]
     */
    public function setContainer(ContainerBuilder $container = null)
    {
        self::$container = $container;
    }

    /**
     * Display container with print_r
     */
    public static function debug()
    {
        echo '<pre>';
        print_r(self::$container);
        echo '</pre>';
    }

    /**
     * Display config with print_r
     */
    public static function debugConfig()
    {
        echo '<pre>';
        print_r(self::$config);
        echo '</pre>';
    }
}
