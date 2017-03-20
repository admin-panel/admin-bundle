<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BetweenDateType extends DateType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'date_format' => null,
        ]);
    }
}
