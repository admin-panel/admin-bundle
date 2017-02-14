<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests\Extension\Doctrine\ColumnType;

use AdminPanel\Component\DataGrid\Tests\Fixtures\Entity as Fixture;
use AdminPanel\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use AdminPanel\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class EntityTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $column = new \AdminPanel\Component\DataGrid\Extension\Doctrine\ColumnType\Entity();
        $column->setName('foo');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        // Call resolve at OptionsResolver.
        $column->setOptions([]);

        $object = new Fixture('object');

        $dataGrid = $this->createMock('AdminPanel\Component\DataGrid\DataGridInterface');
        $dataMapper = $dataMapper = $this->createMock('AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface');

        $dataMapper->expects($this->once())
                   ->method('getData')
                   ->will($this->returnValue(['foo' => 'bar']));

        $dataGrid->expects($this->any())
                 ->method('getDataMapper')
                 ->will($this->returnValue($dataMapper));

        $column->setDataGrid($dataGrid);

        $column->getValue($object);
    }
}
