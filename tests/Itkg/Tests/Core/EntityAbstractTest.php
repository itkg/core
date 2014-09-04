<?php

namespace Itkg\Tests\Core;

class EntityAbstractTest extends \PHPUnit_Framework_TestCase
{
    private $entity;

    public function setUp()
    {
        $this->entity = new \Itkg\Tests\Core\Entity;
        $this->entity->setId(10);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoGetterException()
    {
        $dummy = $this->entity['sub_entity_without_getter_dummy'];
    }

    public function testSetGetData()
    {
        $this->entity->setDataFromArray(array(
            'ENTITY_ID' => 10,
            'ENTITY_SUB_ID' => 11,
        ));

        // Test getter and array access
        $this->assertEquals($this->entity->getId(), 10);
        $this->assertEquals($this->entity['ID'], 10);

        $this->assertEquals($this->entity->getSub()->getId(), 11);
        $this->assertEquals($this->entity['ENTITY_SUB_ID'], 11);

        $this->assertInstanceOf('Itkg\Tests\Core\SubEntity', $this->entity['SUB']);
    }

    public function testCacheData()
    {
        $jsonCacheData = $this->entity->getDataForCache();
        $cacheData = json_decode($jsonCacheData, true);

        $this->assertInternalType('array', $cacheData);

        $entity = new \Itkg\Tests\Core\Entity;
        $entity->setDataFromCache($jsonCacheData);
        $this->assertNotEmpty($entity->getId());
        $this->assertEquals($this->entity->getId(), $entity->getId());

    }
}
