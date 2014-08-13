<?php

namespace Itkg\Core\Command\Script;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFindScriptsWithScriptName()
    {
        $locator = new Locator();

        $locator->setParams(array(
            'path' => __DIR__.'/../../../../',
            'release' => 'mock',
            'scriptName' => 'ticket'
        ));

        $this->assertEquals(1, sizeof($locator->findScripts()));

    }

    public function testFindScriptsWithUnvalidScriptName()
    {
        $locator = new Locator();

        $locator->setParams(array(
            'path' => __DIR__.'/../../../../',
            'release' => 'mock',
            'scriptName' => 'none'
        ));

        $this->assertEmpty($locator->findScripts());
    }
} 