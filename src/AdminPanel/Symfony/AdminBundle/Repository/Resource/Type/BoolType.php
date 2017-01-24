<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminPanel\Symfony\AdminBundle\Repository\Resource\Type;

class BoolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'boolValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'checkbox';
    }
}