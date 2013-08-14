<?php

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\Config\ConfigCache;
use Lemon\Exception\NotFoundException;

/**
 * Class Lemon
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class Lemon
{
    public static $config;
    protected $isDebug;
    protected $extensions;
    protected $cacheFile;
    protected static $container;

    public function __construct($cacheFile, $isDebug = false)
    {
        $this->isDebug = $isDebug;
        $this->cacheFile = $cacheFile;
    }
    
    public function load()
    {
        if(!self::$container) {
            $this->loadContainer($this->cacheFile);
        }
    }
    
    public function registerExtension(ExtensionInterface $extension)
    {
        if(!$this->extensions) {
            $this->extensions = array();
        }
        
        $this->extensions[] = $extension;
    }
   
    public function getExtensions()
    {
        if(!$this->extensions) {
            $this->extensions = array();
        }
        
        return $this->extensions;
    }
    
    public function setExtensions(array $extensions = array())
    {
        $this->extensions = $extensions;
    }
    
    static public function get($key)
    {
        if(self::$container->has($key)) {
            return self::$container->get($key);
        }
        throw new NotFoundException(sprintf('Key %s not found', $key));
    }

    static public function has($key) 
    {
        return self::$container->has($key); 
    }
    
    static public function debug()
    {
        echo '<pre>';
        print_r(self::$container);
        echo '</pre>';
    }

    static public function debugConfig()
    {
        echo '<pre>';
        print_r(self::$config);
        echo '</pre>';
    }
    public function getContainer()
    {
        return self::$container;
    }
    
    public function setContainer(ContainerBuilder $container)
    {
        self::$container = $container;
    }
    
    protected function loadContainer()
    {
        $containerConfigCache = new ConfigCache($this->cacheFile, $this->isDebug);
        
        if (!$containerConfigCache->isFresh()) {
            self::$container = new ContainerBuilder();

            foreach($this->getExtensions() as $extension) {
                self::$container->registerExtension($extension);
                self::$container->loadFromExtension($extension->getAlias());  
            }

            self::$container->compile();

            $dumper = new PhpDumper(self::$container);
            $containerConfigCache->write(
                $dumper->dump(array('class' => 'LemonContainer')),
                self::$container->getResources()
            );
        }else {
            require_once $this->cacheFile;
            self::$container = new LemonContainer();
        }
    }
}