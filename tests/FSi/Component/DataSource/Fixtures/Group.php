<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="groups")
 * @ORM\Entity
 */
class Group
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
