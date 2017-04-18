<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core;

use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use Symfony\Component\Form\FormFactory;

final class CoreExtension extends DriverAbstractExtension
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
     * @return array
     */
    public function getExtendedDriverTypes() : array
    {
        return ['doctrine-dbal'];
    }

    /**
     * @return array
     */
    protected function loadFieldTypes() : array
    {
        return [
            new Field\TextField(),
            new Field\NumberField(),
            new Field\DateTimeField(),
            new Field\BooleanField(),
            new Field\DateField(),
            new Field\CheckboxField()
        ];
    }

    protected function loadFieldTypesExtensions() : array
    {
        return [
            new Field\FormFieldExtension($this->formFactory),
        ];
    }
}
