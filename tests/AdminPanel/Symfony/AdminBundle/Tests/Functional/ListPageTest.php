<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Page\ListPage;
use Symfony\Bundle\FrameworkBundle\Client;

class ListPageTest extends FunctionalTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->createUser('l3l0');
        $this->createUser('otherUser');

        $this->client = self::createClient();
    }

    public function test_that_list_of_elements_is_shown()
    {
        (new ListPage($this->client))
            ->open()
            ->shouldHaveElementsOnTheList(2)
        ;
    }

    public function test_adding_new_element()
    {
        (new ListPage($this->client))
            ->open()
            ->openAddNewElementForm()
            ->fillForm('l3l086')
            ->pressSubmitButton()
            ->shouldHaveElementsOnTheList(3)
        ;
    }

    /**
     * @param string $username
     * @return User
     */
    private function createUser(string $username) : User
    {
        $user = new User();
        $user->username = $username;

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}