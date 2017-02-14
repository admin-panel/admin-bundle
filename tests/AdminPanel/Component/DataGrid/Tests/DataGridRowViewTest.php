<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests;

use AdminPanel\Component\DataGrid\Column\CellViewInterface;
use AdminPanel\Component\DataGrid\Column\ColumnTypeInterface;
use AdminPanel\Component\DataGrid\DataGridRowView;
use AdminPanel\Component\DataGrid\DataGridViewInterface;

class DataGridRowViewTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDataGridRowView()
    {
        $source = 'SOURCE';

        $dataGridView = $this->createMock(DataGridViewInterface::class);

        $cellView = $this->createMock(CellViewInterface::class);

        $column = $this->createMock(ColumnTypeInterface::class);
        $column->expects($this->atLeastOnce())
                ->method('createCellView')
                ->with($source, 0)
                ->will($this->returnValue($cellView));

        $columns = [
            'foo' =>$column
        ];

        $gridRow = new DataGridRowView($dataGridView, $columns, $source, 0);
        $this->assertSame($gridRow->current(), $cellView);

        $this->assertSame($gridRow->getSource(), $source);
    }
}
