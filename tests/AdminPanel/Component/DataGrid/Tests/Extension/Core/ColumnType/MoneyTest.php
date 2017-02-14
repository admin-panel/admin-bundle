<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests\Extension\Core\ColumnType;

use AdminPanel\Component\DataGrid\Extension\Core\ColumnType\Money;
use AdminPanel\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Money
     */
    private $column;

    public function setUp()
    {
        $column = new Money();
        $column->setName('money');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testCurrencyOption()
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00 PLN',
            ]
        );
    }

    public function testCurrencySeparatorOption()
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('value_currency_separator', '$ ');

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00$ PLN',
            ]
        );
    }

    public function testCurrencyDecPointOption()
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('dec_point', '-');

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10-00 PLN',
            ]
        );
    }

    public function testCurrencyDecimalsOption()
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 0);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10 PLN',
            ]
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 5);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00000 PLN',
            ]
        );
    }

    public function testCurrencyPrecisionOption()
    {
        $value = [
            'value' => 10.326
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('precision', 2);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.33 PLN',
            ]
        );

        $value = [
            'value' => 10.324,
        ];
        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.32 PLN',
            ]
        );
    }

    public function testCurrencyThousandsSepOption()
    {
        $value = [
            'value' => 10000,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('thousands_sep', '.');

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.000.00 PLN',
            ]
        );
    }
}
