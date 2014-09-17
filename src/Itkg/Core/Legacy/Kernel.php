<?php

namespace Itkg\Core\Legacy;

use Itkg\Core\ApplicationInterface;
use Itkg\Core\KernelAbstract;
use Itkg\Core\ServiceContainer;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class Kernel
 *
 * Legacy kernel for old projects
 *
 * @package Itkg\Core\Legacy
 */
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

        \Pelican_Cache::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Db::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Request::$eventDispatcher = $container['core']['dispatcher'];
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
     * Load routing from routing files
     *
     * @throws \RuntimeException
     * @return void
     */
    protected function loadRouting()
    {
        $parser = new YamlParser();
        $routes = array();
        foreach ($this->getRoutingFiles() as $file) {
            $routes = array_merge($routes, $parser->parse(file_get_contents($file)));
        }

        foreach ($routes as $name => $route) {
            $this->processRoute($name, $route);
        }

        return $this;
    }

    /**
     * Add route to current routing
     *
     * @param string $name
     * @param mixed $route
     *
     * @return void
     */
    private function processRoute($name, $route)
    {
        $className = null;
        if (isset($route['sequence'])) {
            $className = $route['sequence'];
            \Pelican_Route::addSequence($className);
        }

        if (!isset($route['arguments'])) {
            $route['arguments'] = array();
        }

        if (isset($route['pattern'])) {
            $newRoute = new \Pelican_Route($route['pattern'], $route['arguments'], $className);

            if (isset($route['defaults'])) {
                $newRoute->defaults($route['defaults']);
            }
            if (isset($route['params'])) {
                $newRoute->pushRequestParams($route['params']);
            }
            \Pelican_Route::add($newRoute, $name);
        }
    }

    /**
     * Override some Pelican class with help of Pelican_Loader
     */
    private function overridePelicanLoader()
    {
        /**
         * Pseudo DIC
         */
        \Pelican::$config['PELICAN_LOADER']['External.Smarty'] = array(
            __DIR__ . '/../../../../vendor/smarty/smarty/distribution/libs/Smarty.class.php',
            'Smarty'
        );
        \Pelican::$config['PELICAN_LOADER']['Form'] = array(
            __DIR__ . '/../../../../application/library/Mycanal/Form.php',
            'Mycanal_Form'
        );
    }
}
