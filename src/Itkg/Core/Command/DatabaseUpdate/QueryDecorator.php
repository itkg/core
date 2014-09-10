<?php

namespace Itkg\Core\Command\DatabaseUpdate;

use Itkg\Core\Command\DatabaseUpdate\Query\Parser;
use Itkg\Core\Command\DatabaseUpdate\Template\Loader as TemplateLoader;

class QueryDecorator
{
    /**
     * @var Template\Loader
     */
    private $loader;

    /**
     * @var array
     */
    private $queries = array();

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param \Itkg\Core\Command\DatabaseUpdate\Loader|\Itkg\Core\Command\DatabaseUpdate\Template\Loader $loader
     * @param Query\Parser $parser
     * @param array $queries
     */
    public function __construct(TemplateLoader $loader, Parser $parser, array $queries = array())
    {
        $this->loader = $loader;
        $this->queries = $queries;
        $this->parser = $parser;
    }

    /**
     * Decorate queries using pre or post query template
     * Template add query before / after current query
     * Use query keyword to retrieve template
     *
     * @return $this
     */
    public function decorate()
    {
        $queries = array();
        foreach($this->queries as $query) {
            $this->parser->parse($query);
            /**
             * @fixme : refactor me please
             */
            $queryType = $this->parser->getType();
            if(file_exists('script/templates/pre_'.$queryType.'_template.php')) {
                $queries = array_merge($queries, $this->loader->load('script/templates/pre_'.$queryType.'_template.php')->getQueries());
            }
            $queries[] = $query;
            if(file_exists('script/templates/post_'.$queryType.'_template.php')) {
                $queries = array_merge($queries, $this->loader->load('script/templates/post_'.$queryType.'_template.php')->getQueries());
            }
        }

        $this->queries = $queries;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

} 