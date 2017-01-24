<?php
/**
 * Created by PhpStorm.
 * User: krzychu
 * Date: 24.01.17
 * Time: 10:35
 */

namespace AdminPanel\Symfony\AdminBundle\Entity;

use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceFSiFile as BaseResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AdminPanel\Symfony\AdminBundle\Entity\ResourceRepository\ResourceRepository")
 * @ORM\Table(name="admin_resource")
 */
class FakeResource extends BaseResource
{
}