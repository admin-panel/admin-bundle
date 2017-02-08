<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

final class DatabaseContext
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $username
     * @param bool $hasNewsletter
     * @param float $credits
     * @param int $itemQuantity
     * @param \DateTimeInterface $dateTime
     * @return User
     */
    public function createUser(
        string $username,
        bool $hasNewsletter = false,
        float $credits = 0.00,
        int $itemQuantity = 0,
        \DateTimeInterface $dateTime = null
    ) : User {
        $user = new User();
        $user->username = $username;
        $user->hasNewsletter = $hasNewsletter;
        $user->credits = $credits;
        $user->itemQuantity = $itemQuantity;

        if ($dateTime) {
            $user->createdAt = $dateTime;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}