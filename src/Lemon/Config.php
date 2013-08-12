<?php

namespace Lemon;

/**
 * Classe NotFoundException
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class Config
{
	protected $params;
	protected $requiredParams = array();

	public function getParams()
	{
		if(!is_array($this->params)) {
			$this->params = array();
		}
		return $this->params;
	}

	public function setParams(array $params = array())
	{
		$this->params = $params;
		$this->validateParams();
	}

	public function addParam($key, $value)
	{
		$this->params[$key] = $value;
	}

	public function mergeParams(array $params = array())
	{
		$this->setParams(array_merge($this->getParams(), $params));
	}

	public function validateParams()
	{
		foreach($this->requiredParams as $key) {
			if(!isset($this->params[$key])) {
				throw new \Lemon\Exception\ConfigException(
					sprintf('Parameter %s is required for class %s', $key, get_class($this))
				);
			}
		}
	}
}