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
            new \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\TextField(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\NumberField(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\DateTimeField(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\BooleanField(),
        ];
    }

    protected function loadFieldTypesExtensions() : array
    {
        return [
            new \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\FormFieldExtension($this->formFactory),
        ];
    }
}
