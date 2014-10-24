<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate\Layout;

/**
 * Class Parser
 *
 * Parse a template & replace vars by queries
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Layout
 */
class Parser
{
    /**
     * @var string
     */
    private $path = 'script/templates/layout.template';

    /**
     * @var string
     */
    private $content;

    /**
     * Queries stack
     *
     * @var array
     */
    private $queries;

    /**
     * Available vars
     *
     * @var array
     */
    private $vars = array(
        'alter',
        'grant',
        'create_index',
        'create_synonym',
        'create_table',
        'update',
        'delete',
        'select',
        'insert',
        'drop_index',
        'drop_table',
        'drop_sequence',
        'drop_synonym',
        'all'
    );

    /**
     * Parse template
     *
     * @param array $queries
     * @return $this
     */
    public function parse(array $queries = array())
    {
        $this->queries = $queries;
        if (file_exists($this->path) && null != $this->content = file_get_contents($this->path)) {
            // Parse
            ;
            foreach ($this->vars as $var) {
                $this->processVar($var);
            }
        } else {
            $this->content = implode('', $this->queries);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $var
     */
    private function processVar($var)
    {
        $content = '';
        if (preg_match('/\{' . $var . '\}/', $this->content)) {
            foreach ($this->queries as $key => $query) {
                if ($var == 'all' || $query->getType() == $var) {
                    $content .= $query;
                    unset($this->queries[$key]);
                }
            }
            $this->content = str_replace(sprintf('{%s}', $var), $content, $this->content);
        }
    }
}
