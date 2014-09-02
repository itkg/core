<?php

namespace Itkg\Core\Legacy;

use Itkg\Core\ApplicationInterface;
use Itkg\Core\KernelAbstract;
use Itkg\Core\ServiceContainer;
use Itkg\Core\YamlLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

class Kernel extends KernelAbstract
{

    /**
     * @param ServiceContainer $container
     * @param ApplicationInterface $app
     */
    public function __construct(ServiceContainer $container, ApplicationInterface $app)
    {
        \Pelican::$config = $app->getConfig();
        parent::__construct($container, $app);

        $this->overridePelicanLoader();

        \Pelican_Cache::$eventDispatcher = $container['dispatcher'];
        \Pelican_Db::$eventDispatcher = $container['dispatcher'];
        \Pelican_Request::$eventDispatcher = $container['dispatcher'];
    }
    /**
     * Create a response from a Pelican_Request
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        return $request->execute($this->container)
        ->sendHeaders()
        ->getResponse(
            $this->container['config']['COMPRESSOUPUT'],
            $this->container['config']['DROPCOMMENTS'],
            $this->container['config']['ENCODEEMAIL'],
            $this->container['config']['HIGHLIGHT']
        );
    }

    /**
     * Load Config from config files
     */
    public function loadConfig()
    {
        parent::loadConfig();

        // Load old config file
        include_once __DIR__.'/../../../../../application/configs/config.php';

        \Pelican_Security::base($this->container['config']['security']);
    }
    /**
     * Load routing from routing files
     *
     * @throws \RuntimeException
     * @return void
     */
    public function loadRouting()
    {
        $parser = new YamlParser();
        $routes = array();
        foreach ($this->getRoutingFiles() as $file) {
            $routes = array_merge($routes, $parser->parse(file_get_contents($file)));
        }

        foreach ($routes as $name => $r) {
            $className = null;
            if (isset($r['sequence'])) {
                $className = $r['sequence'];
            }

            if(!isset($r['arguments'])) {
                $r['arguments'] = array();
            }
            if (isset($r['pattern'])) {
                $route = new \Pelican_Route($r['pattern'], $r['arguments'], $className);

                if (isset($r['defaults'])) {
                    $route->defaults($r['defaults']);
                }
                if (isset($r['params'])) {
                    $route->pushRequestParams($r['params']);
                }
                \Pelican_Route::add($route, $name);
            } else {
                \Pelican_Route::addSequence($className);
            }
        }
    }

    /**
     * Override some Pelican class with help of Pelican_Loader
     */
    public function overridePelicanLoader()
    {
        /**
         * Pseudo DIC
         */
        \Pelican::$config['PELICAN_LOADER']['External.Smarty'] = array(
            __DIR__ . '/../../../../vendor/smarty/smarty/distribution/libs/Smarty.class.php',
            'Smarty'
        );
        \Pelican::$config['PELICAN_LOADER']['Form']            = array(
            __DIR__ . '/../../../../application/library/Mycanal/Form.php',
            'Mycanal_Form'
        );
    }
}
