<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Template\Loader;

/**
 * Class Decorator
 *
 * Decorate queries with pre & post template processing
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 */
class Decorator implements DecoratorInterface
{
    /**
     * @var Template\Loader
     */
    private $loader;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * Templates path
     *
     * @var string
     */
    private $path = 'script/templates';

    /**
     * Template pre process filename
     */
    CONST PRE_TEMPLATE_PATH = '%s/pre_%s_template.php';

    /**
     * Template post process filename
     */
    CONST POST_TEMPLATE_PATH = '%s/post_%s_template.php';

    /**
     * @param Template\Loader $loader
     * @param Query\Parser $parser
     * @param array $queries
     */
    public function __construct(Loader $loader, Parser $parser)
    {
        $this->loader  = $loader;
        $this->parser  = $parser;
    }

    /**
     * Decorate a query using pre or post query template
     * Template add query before / after current query
     * Use query keyword to retrieve template
     *
     * @return decorated queries
     */
    public function decorate($query)
    {
        $queryType = $this->parser
            ->parse($query)
            ->getType();

        $queries   = $this->process(sprintf(self::PRE_TEMPLATE_PATH, $this->path, $queryType));

        $queries[] = $query;

        return array_merge($queries, $this->process(sprintf(self::POST_TEMPLATE_PATH, $this->path, $queryType)));
    }

    /**
     * Decorate queries using pre or post query template
     * Template add query before / after current query
     * Use query keyword to retrieve template
     *
     * @return array Decorated queries
     */
    public function decorateAll(array $queries = array())
    {
        $decoratedQueries = array();

        foreach ($queries as $query) {
            $decoratedQueries = array_merge($decoratedQueries, $this->decorate($query));
        }

        return $decoratedQueries;
    }

    /**
     * @param $template
     */
    private function process($template)
    {
        if (file_exists($template)) {
            return $this->loader->load(
                $template,
                $this->parser->getData()
            )->getQueries();
        }

        return array();
    }
}
