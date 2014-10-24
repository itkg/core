<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query;
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
     * @var Loader
     */
    private $loader;

    /**
     * Templates path
     *
     * @var string
     */
    private $templatePath = 'script/templates';

    /**
     * Template pre process filename
     */
    const PRE_TEMPLATE_PATH = '%s/pre_%s_template.php';

    /**
     * Template post process filename
     */
    const POST_TEMPLATE_PATH = '%s/post_%s_template.php';

    /**
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Decorate a query using pre or post query template
     * Template add query before / after current query
     * Use query keyword to retrieve template
     *
     * @param Query $query
     * @return array decorated queries
     */
    public function decorate(Query $query)
    {
        $queries = $this->process(
            $query,
            sprintf(
                self::PRE_TEMPLATE_PATH,
                $this->templatePath,
                $query->getType()
            )
        );

        $queries[] = $query;

        return array_merge(
            $queries,
            $this->process(
                $query,
                sprintf(
                    self::POST_TEMPLATE_PATH,
                    $this->templatePath,
                    $query->getType()
                )
            )
        );
    }

    /**
     * Decorate queries using pre or post query template
     * Template add query before / after current query
     * Use query keyword to retrieve template
     *
     * @param array $queries
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
     * @param \Itkg\Core\Command\DatabaseUpdate\Query $query
     * @param string $template
     * @return array
     */
    private function process(Query $query, $template)
    {
        if (file_exists($template)) {
            return $this->loader->load(
                $template,
                $query->getData()
            )->getQueries();
        }

        return array();
    }

    /**
     * @param string $templatePath
     * @return $this
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}
