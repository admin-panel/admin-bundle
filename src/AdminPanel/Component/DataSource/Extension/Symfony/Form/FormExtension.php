<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Form;

use AdminPanel\Component\DataSource\DataSourceAbstractExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\Driver\DriverExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\EventSubscriber\Events;
use Symfony\Component\Form\FormFactory;

/**
 * Form extension builds Symfony form for given datasource fields.
 *
 * Extension also maintains replacing parameters that came into request into proper form,
 * replacing these parameters into scalars while getting parameters and sets proper
 * options to view.
 */
class FormExtension extends DataSourceAbstractExtension
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
    public function loadDriverExtensions()
    {
        return [
            new DriverExtension($this->formFactory),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new Events(),
        ];
    }
}
