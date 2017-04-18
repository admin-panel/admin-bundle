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
     * @param int $howMany
     * @return User[]
     */
    public function createUsers(int $howMany) : array
    {
        $users = [];

        for($i = 1; $i <= $howMany; ++$i) {
            $users[] = $this->createUser(sprintf('user-%d', $i));
        }

        return $users;
    }

    /**
     * @param string $username
     * @param bool $hasNewsletter
     * @param float $credits
     * @param int $itemQuantity
     * @param \DateTimeInterface $dateTime
     * @param bool $hasSomethingElse
     * @return User
     */
    public function createUser(
        string $username,
        bool $hasNewsletter = false,
        float $credits = 0.00,
        int $itemQuantity = 0,
        \DateTimeInterface $dateTime = null,
        bool $hasSomethingElse = false
    ) : User {
        $user = new User();
        $user->username = $username;
        $user->hasNewsletter = $hasNewsletter;
        $user->credits = $credits;
        $user->itemQuantity = $itemQuantity;
        $user->hasSomethingElse = $hasSomethingElse;

        if ($dateTime) {
            $user->createdAt = $dateTime;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}