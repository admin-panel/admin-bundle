<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use AdminPanel\Component\DataGrid\Column\CellViewInterface;
use AdminPanel\Component\DataGrid\Column\ColumnTypeInterface;
use AdminPanel\Component\DataGrid\Column\HeaderViewInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributesExtensionSpec extends ObjectBehavior
{
    public function it_is_column_extension()
    {
        $this->shouldBeAnInstanceOf('AdminPanel\Component\DataGrid\Column\ColumnTypeExtensionInterface');
    }

    public function it_adds_actions_options(ColumnTypeInterface $column, OptionsResolver $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefined(['header_attr', 'cell_attr', 'container_attr', 'value_attr'])
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('header_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('cell_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('container_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('value_attr', 'array')->shouldBeCalled();
        $optionsResolver->setDefaults([
            'header_attr' => [],
            'cell_attr' => [],
            'container_attr' => [],
            'value_attr' => []
        ])->shouldBeCalled();

        $this->initOptions($column);
    }

    public function it_passes_attributes_to_cell_view(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $column->getOption('cell_attr')->willReturn(['cell attributes']);
        $view->setAttribute('cell_attr', ['cell attributes'])->shouldBeCalled();
        $column->getOption('container_attr')->willReturn(['container attributes']);
        $view->setAttribute('container_attr', ['container attributes'])->shouldBeCalled();
        $column->getOption('value_attr')->willReturn(['value attributes']);
        $view->setAttribute('value_attr', ['value attributes'])->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_passes_attributes_to_header_view(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $column->getOption('header_attr')->willReturn(['header attributes']);
        $view->setAttribute('header_attr', ['header attributes'])->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
