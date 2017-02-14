<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests;

use AdminPanel\Component\DataGrid\Column\ColumnTypeInterface;
use AdminPanel\Component\DataGrid\Column\HeaderViewInterface;
use AdminPanel\Component\DataGrid\Data\DataRowsetInterface;
use AdminPanel\Component\DataGrid\DataGridView;

class DataGridViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AdminPanel\Component\DataGrid\Data\DataRowsetInterface
     */
    private $rowset;

    /**
     * @var \AdminPanel\Component\DataGrid\DataGridView
     */
    private $gridView;

    public function testAddHasGetRemoveColumn()
    {
        $self = $this;

        $column = $this->createMock(ColumnTypeInterface::class);
        $column->expects($this->any())
            ->method('createHeaderView')
            ->will($this->returnCallback(function () use ($self) {
                $headerView = $self->createMock(HeaderViewInterface::class);
                $headerView->expects($self->any())
                    ->method('getName')
                    ->will($self->returnValue('ColumnHeaderView'));

                $headerView->expects($self->any())
                    ->method('getType')
                    ->will($self->returnValue('foo-type'));

                return $headerView;
            }));

        $column->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $columnHeader = $this->createMock(HeaderViewInterface::class);
        $columnHeader->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('foo'));

        $columnHeader->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('foo-type'));

        $columnHeader->expects($this->any())
            ->method('setDataGridView');

        $this->rowset = $this->createMock(DataRowsetInterface::class);
        $this->gridView = new DataGridView('test-grid-view', [$column], $this->rowset);

        $this->assertSame('test-grid-view', $this->gridView->getName());

        $this->assertTrue($this->gridView->hasColumn('foo'));
        $this->assertTrue($this->gridView->hasColumnType('foo-type'));
        $this->assertSame(1, count($this->gridView->getColumns()));
        $this->assertSame($this->gridView->getColumn('foo')->getName(), 'ColumnHeaderView');
        $this->gridView->removeColumn('foo');
        $this->assertFalse($this->gridView->hasColumn('foo'));

        $this->gridView->addColumn($columnHeader);
        $this->assertTrue($this->gridView->hasColumn('foo'));

        $this->gridView->clearColumns();
        $this->assertFalse($this->gridView->hasColumn('foo'));
    }
}
