<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests;

use AdminPanel\Component\DataGrid\Data\IndexingStrategyInterface;
use AdminPanel\Component\DataGrid\DataGridFactory;
use AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface;
use AdminPanel\Component\DataGrid\Tests\Fixtures\FooExtension;

class DataGridFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    protected function setUp()
    {
        $extensions = [
            new FooExtension(),
        ];

        $dataMapper = $this->createMock(DataMapperInterface::class);
        $indexingStrategy = $this->createMock(IndexingStrategyInterface::class);

        $this->factory = new DataGridFactory($extensions, $dataMapper, $indexingStrategy);
    }

    public function testCreateGrids()
    {
        $grid = $this->factory->createDataGrid();
        $this->assertSame('grid', $grid->getName());

        $this->setExpectedException('AdminPanel\Component\DataGrid\Exception\DataGridColumnException');
        $grid = $this->factory->createDataGrid('grid');
    }

    public function testHasColumnType()
    {
        $this->assertTrue($this->factory->hasColumnType('foo'));
        $this->assertFalse($this->factory->hasColumnType('bar'));
    }

    public function testGetColumntype()
    {
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\Tests\Fixtures\ColumnType\FooType', $this->factory->getColumnType('foo'));

        $this->setExpectedException('AdminPanel\Component\DataGrid\Exception\UnexpectedTypeException');
        $this->factory->getColumnType('bar');
    }

    public function testGetDataMapper()
    {
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface', $this->factory->getDataMapper());
    }

    public function testGetIndexingStrategy()
    {
        $this->assertInstanceOf('AdminPanel\Component\DataGrid\Data\IndexingStrategyInterface', $this->factory->getIndexingStrategy());
    }
}
