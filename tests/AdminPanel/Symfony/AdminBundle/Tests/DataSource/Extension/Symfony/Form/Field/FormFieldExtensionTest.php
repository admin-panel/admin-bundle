<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\DataSource\Extension\Symfony\Form\Field;


use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Symfony\AdminBundle\DataSource\Extension\Symfony\Form\Field\FormFieldExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class FormFieldExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFormFieldExtensionForIsNullComparison()
    {
        $optionResolver = $this->createMock(OptionsResolver::class);
        $optionResolver->expects($this->once())
            ->method('setDefaults')
            ->with([
                'form_options' => [
                    'choices' => [
                        'null' => 'is_null_translated',
                        'no_null' => 'is_not_null_translated'
                    ]
                ]
            ]);

        $fieldType = $this->createMock(FieldTypeInterface::class);
        $fieldType->expects($this->atLeastOnce())
            ->method('getOptionsResolver')
            ->will($this->returnValue($optionResolver));
        $fieldType->expects($this->atLeastOnce())
            ->method('getComparison')
            ->will($this->returnValue('isNull'));

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnCallback(function ($id, array $params, $translation_domain) {
                if ($translation_domain != 'AdminPanelBundle') {
                    throw new \RuntimeException(sprintf('Unknown translation domain %s', $translation_domain));
                }
                switch ($id) {
                    case 'datasource.form.choices.is_null':
                        return 'is_null_translated';
                    case 'datasource.form.choices.is_not_null':
                        return 'is_not_null_translated';
                    default:
                        throw new \RuntimeException(sprintf('Unknown translation id %s', $id));
                }
            }));

        $extension = new FormFieldExtension($translator);

        $this->assertSame(
            ['text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'],
            $extension->getExtendedFieldTypes()
        );

        $extension->initOptions($fieldType);
    }

    public function testFormFieldExtensionForBooleanType()
    {
        $optionResolver = $this->createMock(OptionsResolver::class);
        $optionResolver->expects($this->once())
            ->method('setDefaults')
            ->with([
                'form_options' => [
                    'choices' => [
                        '1' => 'yes_translated',
                        '0' => 'no_translated'
                    ]
                ]
            ]);

        $fieldType = $this->createMock(FieldTypeInterface::class);
        $fieldType->expects($this->atLeastOnce())
            ->method('getOptionsResolver')
            ->will($this->returnValue($optionResolver));
        $fieldType->expects($this->atLeastOnce())
            ->method('getType')
            ->will($this->returnValue('boolean'));

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnCallback(function ($id, array $params, $translation_domain) {
                if ($translation_domain != 'AdminPanelBundle') {
                    throw new \RuntimeException(sprintf('Unknown translation domain %s', $translation_domain));
                }
                switch ($id) {
                    case 'datasource.form.choices.yes':
                        return 'yes_translated';
                    case 'datasource.form.choices.no':
                        return 'no_translated';
                    default:
                        throw new \RuntimeException(sprintf('Unknown translation id %s', $id));
                }
            }));

        $extension = new FormFieldExtension($translator);

        $this->assertSame(
            ['text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'],
            $extension->getExtendedFieldTypes()
        );

        $extension->initOptions($fieldType);
    }
}
