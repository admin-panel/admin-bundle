<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BetweenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['label' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['label' => false]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'datasource_between';
    }
}
