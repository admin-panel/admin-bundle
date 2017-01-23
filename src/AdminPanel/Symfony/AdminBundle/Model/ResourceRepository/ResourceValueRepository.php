<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminPanel\Symfony\AdminBundle\Model\ResourceRepository;

interface ResourceValueRepository
{
    /**
     * @param $key
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue
     */
    public function get($key);

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resourceValue
     */
    public function add(ResourceValue $resourceValue);


    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resourceValue
     */
    public function save(ResourceValue $resourceValue);

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resourceValue
     */
    public function remove(ResourceValue $resourceValue);
}
