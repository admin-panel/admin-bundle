<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests;

use AdminPanel\Component\DataGrid\Data\IndexingStrategyInterface;
use AdminPanel\Component\DataGrid\DataGrid;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface;
use AdminPanel\Component\DataGrid\Tests\Fixtures\FooExtension;
use AdminPanel\Component\DataGrid\Tests\Fixtures\ColumnType\FooType;
use AdminPanel\Component\DataGrid\Tests\Fixtures\Entity;

class DataGridTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AdminPanel\Component\DataGrid\DataGridFactoryInterface
     */
    private $factory;

    /**
     * @var IndexingStrategyInterface
     */
    private $indexingStrategy;

    /**
     * @var DataMapperInterface
     */
    private $dataMapper;

    /**
     * @var \AdminPanel\Component\DataGrid\DataGrid
     */
    private $datagrid;

    protected function setUp()
    {
        $this->dataMapper = $this->createMock(DataMapperInterface::class);
        $this->dataMapper->expects($this->any())
            ->method('getData')
            ->will($this->returnCallback(function ($field, $object) {
                switch ($field) {
                    case 'name':
                        return $object->getName();
                    break;
                }
            }));

        $this->dataMapper->expects($this->any())
            ->method('setData')
            ->will($this->returnCallback(function ($field, $object, $value) {
                switch ($field) {
                    case 'name':
                           return $object->setName($value);
                        break;
                }
            }));

        $this->indexingStrategy = $this->createMock(IndexingStrategyInterface::class);
        $this->indexingStrategy->expects($this->any())
            ->method('getIndex')
            ->will($this->returnCallback(function ($object, $dataMapper) {
                if (is_object($object)) {
                    return $object->getName();
                }
                return null;
            }));

        $this->factory = $this->createMock(DataGridFactoryInterface::class);
        $this->factory->expects($this->any())
            ->method('getExtensions')
            ->will($this->returnValue([
                new FooExtension(),
            ]));

        $this->factory->expects($this->any())
            ->method('getColumnType')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue(
                new FooType()
            ));

        $this->factory->expects($this->any())
            ->method('hasColumnType')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue(true));

        $this->datagrid = new DataGrid('grid', $this->factory, $this->dataMapper, $this->indexingStrategy);
    }

    public function testGetName()
    {
        $this->assertSame('grid', $this->datagrid->getName());
    }

    public function testHasAddGetRemoveClearColumn()
    {
        $this->assertFalse($this->datagrid->hasColumn('foo1'));
        $this->datagrid->addColumn('foo1', 'foo');
        $this->assertTrue($this->datagrid->hasColumn('foo1'));
        $this->assertTrue($this->datagrid->hasColumnType('foo'));
        $this->assertFalse($this->datagrid->hasColumnType('this_type_cant_exists'));

        $this->assertInstanceOf('AdminPanel\Component\DataGrid\Tests\Fixtures\ColumnType\FooType', $this->datagrid->getColumn('foo1'));

        $this->assertTrue($this->datagrid->hasColumn('foo1'));
        $column = $this->datagrid->getColumn('foo1');

        $this->datagrid->removeColumn('foo1');
        $this->assertFalse($this->datagrid->hasColumn('foo1'));

        $this->datagrid->addColumn($column);
        $this->assertEquals($column, $this->datagrid->getColumn('foo1'));

        $this->assertEquals(1, count($this->datagrid->getColumns()));

        $this->datagrid->clearColumns();
        $this->assertEquals(0, count($this->datagrid->getColumns()));

        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->getColumn('bar');
    }

    public function testGetDataMapper()
    {
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface', $this->datagrid->getDataMapper());
    }

    public function testGetIndexingStrategy()
    {
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\Data\IndexingStrategyInterface', $this->datagrid->getIndexingStrategy());
    }

    public function testSetData()
    {
        $gridData = [
            new Entity('entity1'),
            new Entity('entity2')
        ];

        $this->datagrid->setData($gridData);

        $this->assertEquals(count($gridData), count($this->datagrid->createView()));

        $gridData = [
            ['some', 'data'],
            ['next', 'data']
        ];

        $this->datagrid->setData($gridData);

        $this->assertEquals(count($gridData), count($this->datagrid->createView()));

        $gridBrokenData = false;
        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->setData($gridBrokenData);
    }

    public function testBindData()
    {
        $gridBrokenData = false;
        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->bindData($gridBrokenData);
    }

    public function testCreateView()
    {
        $this->datagrid->addColumn('foo1', 'foo');
        $gridData = [
            new Entity('entity1'),
            new Entity('entity2')
        ];

        $this->datagrid->setData($gridData);
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\DataGridViewInterface', $this->datagrid->createView());
    }

    public function testSetDataForArray()
    {
        $gridData = [
            ['one'],
            ['two'],
            ['three'],
            ['four'],
            ['bazinga!'],
            ['five'],
        ];

        $this->datagrid->setData($gridData);
        $view = $this->datagrid->createView();

        $keys = [];
        foreach ($view as $row) {
            $keys[] = $row->getIndex();
        }

        $this->assertEquals(array_keys($gridData), $keys);
    }
}
