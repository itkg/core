<?php

namespace Itkg\Core\Command\DatabaseUpdate;

class Query
{
    private $value;

    private $type;

    private $data;

    public function __construct($value)
    {
        $this->value = $value;
        $this->parse();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        $this->parse();

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getType()
    {
        return $this->type;
    }

    protected function parse()
    {
        $query = preg_replace('/OR *REPLACE/', '', $this->value);
        /**
         * @TODO : Grant parse
         */
        if (preg_match(
            '/(CREATE|UPDATE|DELETE|ALTER|INSERT|DROP|SELECT|GRANT) *(SEQUENCE|INDEX|SYNONYM|TABLE|INTO|FROM|) *([a-zA-Z-_]*) */i',
            $query,
            $matches
        )
        ) {
            $this->type = trim(strtolower($matches[1]));
            if(in_array($this->type, array('create', 'drop')))  {
                $this->type = strtolower(trim(sprintf('%s_%s', $matches[1], $matches[2])));
            }

            $this->data = array(
                'identifier' => $matches[3]
            );
        }
    }

    public function __toString()
    {
        return $this->value;
    }
}
