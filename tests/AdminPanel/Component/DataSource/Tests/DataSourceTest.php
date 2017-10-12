<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests;

use AdminPanel\Component\DataSource\DataSourceExtensionInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use AdminPanel\Component\DataSource\Driver\DriverExtensionInterface;
use AdminPanel\Component\DataSource\Driver\DriverInterface;
use AdminPanel\Component\DataSource\Exception\DataSourceException;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\DataSourceViewInterface;
use AdminPanel\Component\DataSource\Tests\Fixtures\TestResult;
use AdminPanel\Component\DataSource\Tests\Fixtures\DataSourceExtension;

/**
 * Tests for DataSource.
 */
class DataSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic creation of DataSource.
     */
    public function testDataSourceCreate()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
    }

    /**
     * Checking assignation of names.
     */
    public function testDataSourceName()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver, 'name1');
        $this->assertEquals($datasource->getName(), 'name1');
        $datasource = new DataSource($driver, 'name2');
        $this->assertEquals($datasource->getName(), 'name2');
    }

    /**
     * Testing exception thrown when creating DataSource with wrong name.
     */
    public function testDataSourceCreateException2()
    {
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver, 'wrong-name');
    }

    /**
     * Testing exception thrown when creating DataSource with empty name.
     */
    public function testDataSourceCreateException3()
    {
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver, '');
    }

    /**
     * Checks loading of extensions.
     */
    public function testDataSourceExtensionsLoad()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $extension1 = $this->createMock(DataSourceExtensionInterface::class);
        $extension2 = $this->createMock(DataSourceExtensionInterface::class);

        $extension1
            ->expects($this->once())
            ->method('loadDriverExtensions')
            ->will($this->returnValue([]))
        ;
        $extension2
            ->expects($this->once())
            ->method('loadDriverExtensions')
            ->will($this->returnValue([]))
        ;

        $datasource->addExtension($extension1);
        $datasource->addExtension($extension2);

        $this->assertEquals(count($datasource->getExtensions()), 2);
    }

    /**
     * Checks exception during field adding.
     */
    public function testWrongFieldAddException1()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->addField('field', 'type');
    }

    /**
     * Checks exception during field adding.
     */
    public function testWrongFieldAddException2()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->addField('field', '', 'type');
    }

    /**
     * Checks exception during field adding.
     */
    public function testWrongFieldAddException3()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->addField('field');
    }

    /**
     * Checks exception during field adding.
     */
    public function testWrongFieldAddException4()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');

        $field = $this->createMock(FieldTypeInterface::class);

        $field
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue(null))
        ;

        $datasource->addField($field);
    }

    /**
     * Checks creating, adding, getting and deleting fields.
     */
    public function testFieldManipulation()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);

        $field = $this->createMock(FieldTypeInterface::class);

        $field
            ->expects($this->once())
            ->method('setName')
            ->with('name1')
        ;

        $field
            ->expects($this->once())
            ->method('setComparison')
            ->with('comp1')
        ;

        $field
            ->expects($this->once())
            ->method('setOptions')
        ;

        $field
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $driver
            ->expects($this->once())
            ->method('getFieldType')
            ->with('text')
            ->will($this->returnValue($field))
        ;

        $datasource->addField('name1', 'text', 'comp1');

        $this->assertEquals(count($datasource->getFields()), 1);
        $this->assertTrue($datasource->hasField('name1'));
        $this->assertFalse($datasource->hasField('wrong'));

        $datasource->clearFields();
        $this->assertEquals(count($datasource->getFields()), 0);

        $datasource->addField($field);
        $this->assertEquals(count($datasource->getFields()), 1);
        $this->assertTrue($datasource->hasField('name'));
        $this->assertFalse($datasource->hasField('name1'));
        $this->assertFalse($datasource->hasField('name2'));

        $this->assertEquals($field, $datasource->getField('name'));

        $this->assertTrue($datasource->removeField('name'));
        $this->assertEquals(count($datasource->getFields()), 0);
        $this->assertFalse($datasource->removeField('name'));

        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->getField('wrong');
    }

    /**
     * Checks behaviour when binding arrays and scalars.
     */
    public function testBindParametersException()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $datasource->bindParameters([]);
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->bindParameters('nonarray');
    }

    /**
     * Checks behaviour at bind and get data.
     */
    public function testBindAndGetResult()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);
        $field = $this->createMock(FieldTypeInterface::class);
        $testResult = new TestResult();

        $firstData = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => ['field' => 'value', 'other' => 'notimportant'],
            ],
        ];
        $secondData = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => ['somefield' => 'somevalue'],
            ],
        ];

        $field
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('field'))
        ;

        $field
            ->expects($this->exactly(2))
            ->method('bindParameter')
        ;

        $driver
            ->expects($this->once())
            ->method('getResult')
            ->with(['field' => $field])
            ->will($this->returnValue($testResult))
        ;

        $datasource->addField($field);
        $datasource->bindParameters($firstData);
        $datasource->bindParameters($secondData);

        $result = $datasource->getResult();
    }

    /**
     * Tests exception when driver returns scalar.
     */
    public function testWrongResult1()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);

        $driver
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue('scalar'))
        ;
        $this->expectException(DataSourceException::class);
        $datasource->getResult();
    }

    /**
     * Tests exception when driver return object, that doesn't implement Countable and IteratorAggregate interfaces.
     */
    public function testWrongResult2()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);

        $driver
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(new \stdClass()))
        ;
        $this->expectException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $datasource->getResult();
    }

    /**
     * Checks if parameters for pagination are forwarded to driver.
     */
    public function testPagination()
    {
        $driver = $this->createMock(DriverInterface::class);
        $datasource = new DataSource($driver);

        $max = 20;
        $first = 40;

        $datasource->setMaxResults($max);
        $datasource->setFirstResult($first);

        $this->assertEquals($datasource->getMaxResults(), $max);
        $this->assertEquals($datasource->getFirstResult(), $first);
    }

    /**
     * Checks if exception is thrown when page is out of bound
     */
    public function testPageNotFoundException()
    {
        $driver = $this->createMock(DriverInterface::class);
        $dataSource = new DataSource($driver);

        $driver
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(
                new class implements \IteratorAggregate, \Countable
                {
                    public function count()
                    {
                        return 12;
                    }

                    public function getIterator()
                    {
                        return new \ArrayIterator([]);
                    }
                }
            ));

        $this->expectException('AdminPanel\Component\DataSource\Exception\PageNotFoundException');
        $dataSource->getResult();
    }

    /**
     * Checks preGetParameters and postGetParameters calls.
     */
    public function testGetParameters()
    {
        $driver = $this->createMock(DriverInterface::class);
        $field = $this->createMock(FieldTypeInterface::class);
        $field2 = $this->createMock(FieldTypeInterface::class);

        $datasource = new DataSource($driver);

        $field
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('key'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getParameter')
            ->with([])
        ;

        $field2
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('key2'))
        ;

        $field2
            ->expects($this->atLeastOnce())
            ->method('getParameter')
            ->with([])
        ;

        $datasource->addField($field);
        $datasource->addField($field2);
        $datasource->getParameters();
    }

    /**
     * Checks view creation.
     */
    public function testViewCreation()
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(new ArrayCollection()))
        ;

        $datasource = new DataSource($driver);
        $view = $datasource->createView();
        $this->assertTrue($view instanceof DataSourceViewInterface);
    }

    /**
     * Checks factory assignation.
     */
    public function testFactoryAssignation()
    {
        $driver = $this->createMock(DriverInterface::class);
        $factory = $this->createMock(DataSourceFactoryInterface::class);

        $datasource = new DataSource($driver);
        $datasource->setFactory($factory);
        $this->assertEquals($datasource->getFactory(), $factory);
    }

    /**
     * Checks fetching parameters of all and others datasources.
     */
    public function testGetAllAndOthersParameters()
    {
        $driver = $this->createMock(DriverInterface::class);
        $factory = $this->createMock(DataSourceFactoryInterface::class);

        $datasource = new DataSource($driver);

        $factory
            ->expects($this->once())
            ->method('getOtherParameters')
            ->with($datasource)
        ;

        $factory
            ->expects($this->once())
            ->method('getAllParameters')
        ;

        $datasource->setFactory($factory);
        $datasource->getOtherParameters();
        $datasource->getAllParameters();
    }

    /**
     * Check if datasource loads extensions for driver that comes from its own extensions.
     */
    public function testDriverExtensionLoading()
    {
        $driver = $this->createMock(DriverInterface::class);
        $extension = $this->createMock(DataSourceExtensionInterface::class);
        $driverExtension = $this->createMock(DriverExtensionInterface::class);

        $extension
            ->expects($this->once())
            ->method('loadDriverExtensions')
            ->will($this->returnValue([$driverExtension]))
        ;

        $driverExtension
            ->expects($this->once())
            ->method('getExtendedDriverTypes')
            ->will($this->returnValue(['fake']))
        ;

        $driver
            ->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('fake'))
        ;

        $driver
            ->expects($this->once())
            ->method('addExtension')
            ->with($driverExtension)
        ;

        $datasource = new DataSource($driver);
        $datasource->addExtension($extension);
    }

    /**
     * Checks extensions calls.
     */
    public function testExtensionsCalls()
    {
        $driver = $this->createMock(DriverInterface::class);
        $extension = new DataSourceExtension();
        $datasource = new DataSource($driver);
        $datasource->addExtension($extension);

        $testResult = new TestResult();
        $driver
            ->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($testResult))
        ;

        $datasource->bindParameters([]);
        $this->assertEquals(['preBindParameters', 'postBindParameters'], $extension->getCalls());
        $extension->resetCalls();

        $datasource->getResult();
        $this->assertEquals(['preGetResult', 'postGetResult'], $extension->getCalls());
        $extension->resetCalls();

        $datasource->getParameters();
        $this->assertEquals(['preGetParameters', 'postGetParameters'], $extension->getCalls());
        $extension->resetCalls();

        $datasource->createView();
        $this->assertEquals(['preBuildView', 'postBuildView'], $extension->getCalls());
    }
}
