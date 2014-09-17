<?php

namespace Itkg\Core\Command\DatabaseUpdate;

class Query
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->parse();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @param bool $forcedParse
     * @return $this
     */
    public function setValue($value, $forcedParse = true)
    {
        $this->value = $value;

        if ($forcedParse) {
            $this->parse();
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Parse query
     * Extract type & extra data
     */
    protected function parse()
    {
        $query = preg_replace('/OR *REPLACE/', '', $this->value);
        /**
         * @TODO : Grant parse
         */
        if (preg_match(
            '/(CREATE|UPDATE|DELETE|ALTER|INSERT|DROP|SELECT|GRANT) *(SEQUENCE|INDEX|SYNONYM|TABLE|INTO|FROM|VIEW|) *([a-zA-Z-_]*) */i',
            $query,
            $matches
        )
        ) {
            $this->type = trim(strtolower($matches[1]));
            if (in_array($this->type, array('create', 'drop'))) {
                $this->type = strtolower(trim(sprintf('%s_%s', $matches[1], $matches[2])));
            }

            $this->data = array(
                'identifier' => $matches[3]
            );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
