<?php

namespace Itkg\Core\Legacy;

use Itkg\Core\ApplicationInterface;
use Itkg\Core\Event\KernelEvent;
use Itkg\Core\Event\RequestEvent;
use Itkg\Core\Event\ResponseEvent;
use Itkg\Core\KernelAbstract;
use Itkg\Core\Matcher\RequestMatcher;
use Itkg\Core\Resolver\ControllerResolver;;
use Itkg\Core\Route\Route;
use Itkg\Core\ServiceContainer;
use Itkg\Core\YamlLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser as YamlParser;

class Kernel extends KernelAbstract
{
    /**
     * @param ServiceContainer $container
     * @param ApplicationInterface $app
     * @param \Itkg\Core\Resolver\ControllerResolver $resolver
     */
    public function __construct(ServiceContainer $container, ApplicationInterface $app, ControllerResolver $resolver)
    {
        \Pelican::$config = $app->getConfig();
        parent::__construct($container, $app, $resolver);

        $this->overridePelicanLoader();

        \Pelican_Cache::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Db::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Request::$eventDispatcher = $container['core']['dispatcher'];
        \Backoffice_Div_Helper::$kernel = $this;
        $this->resolver->setPath($this->container['config']['APPLICATION_CONTROLLERS']);

    }

    /**
     * Load routing from routing files
     *
     * @throws \RuntimeException
     * @return $this
     */
    protected function loadRouting()
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
                $this->container['core']['router']->addRouteSequence($className);
            }


            if (isset($r['pattern'])) {
                if (!isset($r['arguments'])) {
                    $r['arguments'] = array();
                }

                $route = new Route($r['pattern'], $r['arguments'], $className);

                if (isset($r['defaults'])) {
                    $route->defaults($r['defaults']);
                }
                if (isset($r['params'])) {
                    $route->pushRequestParams($r['params']);
                }
                $this->container['core']['router']->addRoute($route, $name);
            }
        }

        return $this;
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
