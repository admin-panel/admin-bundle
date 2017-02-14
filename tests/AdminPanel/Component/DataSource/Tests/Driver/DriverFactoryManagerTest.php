<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Driver;

use AdminPanel\Component\DataSource\Driver\Collection\CollectionFactory;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineFactory;
use AdminPanel\Component\DataSource\Driver\DriverFactoryManager;

/**
 * Basic tests for Doctrine driver.
 */
class DriverFactoryManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicManagerOperations()
    {
        $doctrineFactory = $this->getMockBuilder(DoctrineFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $doctrineFactory->expects($this->any())
            ->method('getDriverType')
            ->will($this->returnValue('doctrine'));

        $collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collectionFactory->expects($this->any())
            ->method('getDriverType')
            ->will($this->returnValue('collection'));


        $manager = new DriverFactoryManager([
            $doctrineFactory,
            $collectionFactory
        ]);

        $this->assertTrue($manager->hasFactory('doctrine'));
        $this->assertTrue($manager->hasFactory('collection'));

        $this->assertSame($doctrineFactory, $manager->getFactory('doctrine'));
        $this->assertSame($collectionFactory, $manager->getFactory('collection'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidFactory()
    {
        $notFactory = new \DateTime();

        $manager = new DriverFactoryManager([
            $notFactory,
        ]);
    }
}
