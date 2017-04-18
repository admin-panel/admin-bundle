<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin_panel_users")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $username;

    /**
     * @ORM\Column(type="boolean")
     */
    public $hasNewsletter = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $hasSomethingElse;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    public $credits = 0.00;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $itemQuantity = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}