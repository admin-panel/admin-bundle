<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Form\Driver;

use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\Field\FormFieldExtension;
use Symfony\Component\Form\FormFactory;

/**
 * Driver extension for form that loads fields extension.
 */
class DriverExtension extends DriverAbstractExtension
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes() : array
    {
        return [
            'doctrine', //deprecated since version 1.4
            'doctrine-orm'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return [
            new FormFieldExtension($this->formFactory),
        ];
    }
}
