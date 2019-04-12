<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Extension\Symfony;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Component\DataSource\Event\FieldEvent\ParameterEventArgs;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\Extension\DatasourceExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\Driver\DriverExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\EventSubscriber\Events;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\Type\BetweenType;
use AdminPanel\Component\DataSource\Field\FieldAbstractExtension;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Field\FieldView;
use AdminPanel\Component\DataSource\Field\FieldViewInterface;
use AdminPanel\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\TestCoreExtension;
use Symfony\Component\Form;
use Symfony\Component\Security;
use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\Event\DataSourceEvent\ViewEventArgs;
use AdminPanel\Component\DataSource\Tests\Fixtures\Form as TestForm;

/**
 * Tests for Symfony Form Extension.
 */
class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides types.
     *
     * @return array
     */
    public static function typesProvider()
    {
        return [
            ['text'],
            ['number'],
            ['time'],
            ['date'],
            ['datetime'],
        ];
    }

    /**
     * Provides field types, comparison types and expected form input types.
     *
     * @return array
     */
    public static function fieldTypesProvider()
    {
        return [
            ['text', 'isNull', 'choice'],
            ['text', 'eq', 'text'],
            ['number', 'isNull', 'choice'],
            ['number', 'eq', 'text'],
            ['datetime', 'isNull', 'choice'],
            ['datetime', 'eq', 'datetime'],
            ['datetime', 'between', 'datasource_between'],
            ['time', 'isNull', 'choice'],
            ['time', 'eq', 'time'],
            ['date', 'isNull', 'choice'],
            ['date', 'eq', 'date'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Symfony\Component\Form\Form')) {
            $this->markTestSkipped('Symfony Form needed!');
        }
    }

    /**
     * Returns mock of FormFactory.
     *
     * @return object
     */
    private function getFormFactory()
    {
        $typeFactory = new Form\ResolvedFormTypeFactory();
        $typeFactory->createResolvedType(new BetweenType(), []);
        $registry = new Form\FormRegistry(
            [
                new TestCoreExtension(),
                new Form\Extension\Core\CoreExtension(),
                new Form\Extension\Csrf\CsrfExtension(new Security\Csrf\CsrfTokenManager()),
                new DatasourceExtension()
            ],
            $typeFactory
        );
        return new Form\FormFactory($registry);
    }

    /**
     * Checks creation of DriverExtension.
     */
    public function testCreateDriverExtension()
    {
        $formFactory = $this->getFormFactory();
        $extension = new DriverExtension($formFactory);
    }

    /**
     * Tests if driver extension has all needed fields.
     */
    public function testDriverExtension()
    {
        $formFactory = $this->getFormFactory();
        $extension = new DriverExtension($formFactory);

        $this->assertTrue($extension->hasFieldTypeExtensions('text'));
        $this->assertTrue($extension->hasFieldTypeExtensions('number'));
        $this->assertTrue($extension->hasFieldTypeExtensions('entity'));
        $this->assertTrue($extension->hasFieldTypeExtensions('date'));
        $this->assertTrue($extension->hasFieldTypeExtensions('time'));
        $this->assertTrue($extension->hasFieldTypeExtensions('datetime'));
        $this->assertFalse($extension->hasFieldTypeExtensions('wrong'));

        $extension->getFieldTypeExtensions('text');
        $extension->getFieldTypeExtensions('number');
        $extension->getFieldTypeExtensions('entity');
        $extension->getFieldTypeExtensions('date');
        $extension->getFieldTypeExtensions('time');
        $extension->getFieldTypeExtensions('datetime');
        $this->setExpectedException('AdminPanel\Component\DataSource\Exception\DataSourceException');
        $extension->getFieldTypeExtensions('wrong');
    }

    public function testFormOrder()
    {
        $datasource = $this->createMock('AdminPanel\Component\DataSource\DataSourceInterface');
        $view = $this->createMock('AdminPanel\Component\DataSource\DataSourceViewInterface');

        $fields = [];
        $fieldViews = [];
        for ($i = 0; $i < 15; $i++) {
            $field = $this->createMock('AdminPanel\Component\DataSource\Field\FieldTypeInterface');
            $fieldView = $this->createMock('AdminPanel\Component\DataSource\Field\FieldViewInterface');

            unset($order);
            if ($i < 5) {
                $order = -4 + $i;
            } elseif ($i > 10) {
                $order = $i - 10;
            }

            $field
                ->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('field' . $i))
            ;

            $field
                ->expects($this->any())
                ->method('hasOption')
                ->will($this->returnValue(isset($order)))
            ;

            if (isset($order)) {
                $field
                    ->expects($this->any())
                    ->method('getOption')
                    ->will($this->returnValue($order))
                ;
            }

            $fieldView
                ->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('field' . $i))
            ;

            $fields['field' . $i] = $field;
            $fieldViews['field' . $i] = $fieldView;
            if (isset($order)) {
                $names['field' . $i] = $order;
            } else {
                $names['field' . $i] = null;
            }
        }

        $datasource
            ->expects($this->any())
            ->method('getField')
            ->will($this->returnCallback(function ($field) use ($fields) {
                return $fields[$field];
            }))
        ;

        $view
            ->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue($fieldViews))
        ;

        $view
            ->expects($this->once())
            ->method('setFields')
            ->will($this->returnCallback(function (array $fields) {
                $names = [];
                foreach ($fields as $field) {
                    $names[] = $field->getName();
                }

                $this->assertSame(
                    [
                        'field0', 'field1', 'field2', 'field3', 'field5',
                        'field6', 'field7', 'field8', 'field9', 'field10', 'field4',
                        'field11', 'field12', 'field13', 'field14'
                    ],
                    $names
                );
            }))
        ;

        $event = new ViewEventArgs($datasource, $view);
        $subscriber = new Events();
        $subscriber->postBuildView($event);
    }

    /**
     * Checks fields behaviour.
     *
     * @dataProvider typesProvider
     */
    public function testFields($type)
    {
        $formFactory = $this->getFormFactory();
        $extension = new DriverExtension($formFactory);
        $field = $this->createMock(FieldTypeInterface::class);
        $datasource = $this->createMock(DataSource::class);

        $datasource
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('datasource'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $field
            ->expects($this->any())
            ->method('getDataSource')
            ->will($this->returnValue($datasource))
        ;

        $field
            ->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type))
        ;

        $field
            ->expects($this->any())
            ->method('hasOption')
            ->will($this->returnCallback(function ($option) use ($type) {
                return (($type == 'number') && ($option =='form_type'));
            }))
        ;

        $field
            ->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function ($option) use ($type) {
                switch ($option) {
                    case 'form_filter':
                        return true;
                    case 'form_type':
                        if ($type == 'number') {
                            return 'text';
                        } else {
                            return null;
                        }
                    case 'form_from_options':
                    case 'form_to_options':
                    case 'form_options':
                        return [];
                }
            }))
        ;

        $extensions = $extension->getFieldTypeExtensions($type);

        if ($type == 'datetime') {
            $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' =>
                [
                    'date' => ['year' => (new \DateTime('now'))->format('Y'), 'month' => 12, 'day' => 12],
                    'time' => ['hour' => 12, 'minute' => 12],
                ],
            ]]];
            $parameters2 = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' => new \DateTime((new \DateTime('now'))->format('Y-12-12 12:12:00'))]]];
        } elseif ($type == 'time') {
            $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' =>
                [
                    'hour' => 12,
                    'minute' => 12,
                ],
            ]]];
            $parameters2 = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' => new \DateTime(date('Y-m-d', 0).' 12:12:00')]]];
        } elseif ($type == 'date') {
            $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' =>
                [
                    'year' => (new \DateTime('now'))->format('Y'),
                    'month' => 10,
                    'day' => 10,
                ],
            ]]];
            $parameters2 = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' => new \DateTime((new \DateTime('now'))->format('Y-10-10'))]]];
        } elseif ($type == 'number') {
            $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' => 123]]];
            $parameters2 = $parameters;
        } else {
            $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' => 'value']]];
            $parameters2 = $parameters;
        }

        $args = new ParameterEventArgs($field, $parameters);
        foreach ($extensions as $ext) {
            $this->assertTrue($ext instanceof FieldAbstractExtension);
            $ext->preBindParameter($args);
        }
        $parameters = $args->getParameter();

        $this->assertEquals($parameters2, $parameters);
        $fieldView = $this->createMock(FieldViewInterface::class);

        $fieldView
            ->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->will($this->returnCallback(function ($attribute, $value) use ($type) {
                if ($attribute == 'form') {
                    $this->assertInstanceOf('\Symfony\Component\Form\FormView', $value);
                }
            }))
        ;

        $args = new \AdminPanel\Component\DataSource\Event\FieldEvent\ViewEventArgs($field, $fieldView);
        foreach ($extensions as $ext) {
            $ext->postBuildView($args);
        }
    }

    /**
     * Checks types of generated fields
     *
     * @dataProvider fieldTypesProvider
     */
    public function testFormFields($type, $comparison, $expected)
    {
        $formFactory = $this->getFormFactory();
        $extension = new DriverExtension($formFactory);
        $field = $this->createMock(FieldTypeInterface::class);
        $datasource = $this->createMock(DataSource::class);

        $datasource
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('datasource'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $field
            ->expects($this->any())
            ->method('getDataSource')
            ->will($this->returnValue($datasource))
        ;

        $field
            ->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type))
        ;

        $field
            ->expects($this->any())
            ->method('hasOption')
            ->will($this->returnCallback(function ($option) use ($type) {
                return (($type == 'number') && ($option =='form_type'));
            }))
        ;

        $field
            ->expects($this->any())
            ->method('getComparison')
            ->will($this->returnValue($comparison))
        ;

        $field
            ->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function ($option) use ($type) {
                switch ($option) {
                    case 'form_null_value':
                        return 'empty';

                    case 'form_not_null_value':
                        return 'not empty';

                    case 'form_filter':
                        return true;

                    case 'form_type':
                        if ($type == 'number') {
                            return Form\Extension\Core\Type\TextType::class;
                        } else {
                            return null;
                        }

                    case 'form_from_options':
                    case 'form_to_options':
                    case 'form_options':
                        return [];
                }
            }))
        ;
        $extensions = $extension->getFieldTypeExtensions($type);

        $parameters = ['datasource' => [DataSourceInterface::PARAMETER_FIELDS => ['name' =>
            'null'
        ]]];

        $args = new ParameterEventArgs($field, $parameters);

        $view = new FieldView($field);
        $viewEventArgs = new \AdminPanel\Component\DataSource\Event\FieldEvent\ViewEventArgs($field, $view);

        foreach ($extensions as $ext) {
            $ext->preBindParameter($args);
            $ext->postBuildView($viewEventArgs);
        }

        $form = $viewEventArgs->getView()->getAttribute('form');

        $this->assertEquals($expected, $form['fields']['name']->vars['block_prefixes'][1]);

        if ($comparison == 'isNull') {
            $this->assertEquals(
                'empty',
                $form['fields']['name']->vars['choices'][0]->label
            );
            $this->assertEquals(
                'not empty',
                $form['fields']['name']->vars['choices'][1]->label
            );
        }
    }
}
