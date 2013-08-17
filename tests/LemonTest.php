<?php

/**
 * Lemon test class
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class LemonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lemon
     */
    protected $object;
    protected $cacheFile;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {        
        $this->cacheFile = __DIR__.'/../var/cache/test_cache.php';
        $this->object = new \Lemon($this->cacheFile);
        if(file_exists($this->cacheFile))
            unlink($this->cacheFile);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers Lemon::__construct
     */
    public function test__construct()
    {
        $this->object = new \Lemon($this->cacheFile, true);
    }

    /**
     * @covers Lemon::getExtensions
     */
    public function testgetExtensions()
    {
        $this->assertEquals(array(), $this->object->getExtensions());
    }

    /**
     * @covers Lemon::getExtensions
     * @covers Lemon::setExtensions
     */
    public function testsetExtensions()
    {
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->setExtensions($extensions);
        $this->assertEquals($extensions, $this->object->getExtensions());
    }

    /**
     * @covers Lemon::getExtensions
     * @covers Lemon::registerExtension
     */
    public function testregisterExtension()
    {
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->registerExtension($extensions[0]);
        $this->assertEquals($extensions, $this->object->getExtensions());
    } 

    /**
     * @covers Lemon::load
     * @covers Lemon::setContainer
     * @covers Lemon::has
     */
    public function testload()
    {
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->setExtensions($extensions);
        $this->object->load();
        $this->assertEquals(true, file_exists($this->cacheFile));

        $this->assertEquals(true, \Lemon::has('mock.config'));
        $this->object->setContainer(null);
        $this->object->load();
        $this->assertEquals(true, file_exists($this->cacheFile));
        $this->assertEquals(true, \Lemon::has('mock.config'));

    } 

    /**
     * @covers Lemon::setContainer
     * @covers Lemon::getContainer
     */
    public function testgetContainer()
    {
        $this->object->setContainer(null);
        $this->assertNull($this->object->getContainer());
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->setExtensions($extensions);
        $this->object->load();
        $container = $this->object->getContainer();
        $this->assertEquals($container->get('mock.config'), $this->object->get('mock.config'));

    }

    /**
     * @covers Lemon::get
     */
    public function testget()
    {
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->setExtensions($extensions);
        $this->object->load();
        Lemon::$config['mock.config'] = array('PARAMETER_ONE' => 'ONE');
        $config = new \Lemon\Config();
        $config->setParams(Lemon::$config['mock.config']);
        $this->assertEquals($config, $this->object->get('mock.config'));

        try {
            $this->object->get('unknown');
        }catch(\Lemon\Exception\NotFoundException $e) {
            return;
        }

        $this->fails('exception not raised');
    }

    /**
     * @covers Lemon::debug
     */
    public function testdebug()
    {
        $extensions = array(new \Lemon\Mock\DependencyInjection\LemonMockExtension());
        $this->object->setExtensions($extensions);
        $this->object->load();
        
        ob_start();
        echo'<pre>';print_r($this->object->getContainer());echo'</pre>';
        $template = ob_get_contents();
        ob_end_clean();
        
        ob_start();

        $this->object->debug();

        ob_end_flush();
        $this->assertEquals(ob_get_contents(), $template);
    }

    /**
     * @covers Lemon::debugConfig
     */
    public function testdebugConfig()
    {
        $config = array('PARAMETER_ONE' => 'ONE');
        \Lemon::$config = $config;
        ob_start();
        echo'<pre>';print_r($config);echo'</pre>';
        $template = ob_get_contents();
        ob_end_clean();
        
        ob_start();

        $this->object->debugConfig();

        ob_end_flush();
        $this->assertEquals(ob_get_contents(), $template);
    }
}