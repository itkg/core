<?php

namespace Lemon;

/**
 * Lemon\Config test class
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lemon\Config
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {         
        $this->object = new \Lemon\Config();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * Get parameters
     *
     * @covers Lemon\Config::getParams
     */
    public function testgetParams()
    {
        $this->assertEquals(array(), $this->object->getParams());
    }

    /**
     * Get required parameters
     *
     * @covers Lemon\Config::getRequiredParams
     */
    public function testgetRequiredParams()
    {
        $this->assertEquals(array(), $this->object->getRequiredParams());
    }

    /**
     * Get parameters
     *
     * @covers Lemon\Config::getParam
     */
    public function testgetParam()
    {
        $params = array('PARAMETER_ONE' => 'ONE', 'PARAMETER_TWO' => 'TWOs');
        $this->object->setParams($params);
        $this->assertEquals('ONE', $this->object->getParam('PARAMETER_ONE'));

        try {
            $this->object->getParam('PARAMETER_UNKNOWN');
        }catch(\Lemon\Exception\NotFoundException $e) {
            return;
        }
        $this->fail('Exception not raised');
    }

    /**
     * Set parameters
     *
     * @covers Lemon\Config::setParams
     * @covers Lemon\Config::getParams
     */
    public function testsetParams()
    {
        $params = array('PARAMETER_ONE' => 'ONE', 'PARAMETER_TWO' => 'TWO');
        $this->object->setParams($params);
        $this->assertEquals($params, $this->object->getParams());
    }

    /**
     * Set parameters
     *
     * @covers Lemon\Config::setRequiredParams
     * @covers Lemon\Config::getRequiredParams
     */
    public function testsetRequiredParams()
    {
        $params = array('PARAMETER_ONE');
        $this->object->setRequiredParams($params);
        $this->assertEquals($params, $this->object->getRequiredParams());
    }
    
    /**
     * @covers Lemon\Config::setParam
     */
    public function testsetParam()
    {
        $params = array('PARAMETER_ONE' => 'ONE');
        $this->object->setParams($params);
        $this->object->setParam('PARAMETER_ONE', 'TWO');
        $this->assertEquals('TWO', $this->object->getParam('PARAMETER_ONE'));
    }
    
    /**
     * @covers Lemon\Config::addParam
     */
    public function testaddParam()
    {
        $params = array('PARAMETER_ONE' => 'ONE');
        $this->object->setParams($params);
        $this->object->addParam('PARAMETER_TWO', 'TWO');
        $this->assertEquals('ONE', $this->object->getParam('PARAMETER_ONE'));
        $this->assertEquals('TWO', $this->object->getParam('PARAMETER_TWO'));
    }

    /**
     * @covers Lemon\Config::validateParams
     */
    public function testvalidateParams()
    {
        $params = array('PARAMETER_ONE' => 'ONE');

        $requiredParams = array('PARAMETER_ONE');
        $this->object->setRequiredParams($requiredParams);
        $this->object->setParams($params);
        try {
            $this->object->validateParams();
        }catch(\Lemon\Exception\ConfigException $e) {
            $this->fail('Exception raised');
        }

        $requiredParams = array('PARAMETER_TWO');
        $this->object->setRequiredParams($requiredParams);

        try {
            $this->object->validateParams();
        }catch(\Lemon\Exception\ConfigException $e) {
            return;
        }
        $this->fail('Exception not raised');
    }


    /**
     * @covers Lemon\Config::mergeParams
     */
    public function testmergeParams()
    {
        $parameters = array('PARAMETER_ONE' => 'ONE');
        $this->object->setParams($parameters);
        
        $this->assertEquals(
            'ONE',
            $this->object->getParam('PARAMETER_ONE')
        );
        $parametersTwo = array('PARAMETER_ONE' => 'TWO', 'PARAMETER_TWO' => 'TWO');
        $this->object->mergeParams($parametersTwo);
        
        $this->assertEquals(
            'TWO',
            $this->object->getParam('PARAMETER_ONE')
        );
    }
}