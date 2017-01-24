<?php
/**
 * Created by PhpStorm.
 * User: krzychu
 * Date: 24.01.17
 * Time: 10:35
 */

namespace AdminPanel\Symfony\AdminBundle\Entity;

use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceFSiFile as BaseResource;

/**
 * @ORM\Entity(repositoryClass="FSi\Bundle\ResourceRepositoryBundle\Entity\ResourceRepository")
 * @ORM\Table(name="fsi_resource")
 */
class FakeResource extends BaseResource
{
}