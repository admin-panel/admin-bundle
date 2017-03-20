<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataSource\Extension\Symfony\Form\Field;

use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Field\FieldAbstractExtension;
use Symfony\Component\Translation\TranslatorInterface;

class FormFieldExtension extends FieldAbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return ['text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
        if ($field->getComparison() == 'isNull') {
            $field->getOptionsResolver()
                ->setDefaults([
                    'form_options' => [
                        'choices' => [
                            'null' => $this->translator->trans('datasource.form.choices.is_null', [], 'AdminPanelBundle'),
                            'no_null' => $this->translator->trans('datasource.form.choices.is_not_null', [], 'AdminPanelBundle')
                        ]
                    ]
                ]);
        } elseif ($field->getType() == 'boolean') {
            $field->getOptionsResolver()
                ->setDefaults([
                    'form_options' => [
                        'choices' => [
                            '1' => $this->translator->trans('datasource.form.choices.yes', [], 'AdminPanelBundle'),
                            '0' => $this->translator->trans('datasource.form.choices.no', [], 'AdminPanelBundle')
                        ]
                    ]
                ]);
        }
    }
}
