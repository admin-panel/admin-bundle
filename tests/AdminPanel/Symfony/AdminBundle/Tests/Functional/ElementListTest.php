<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Page\UserListPage;

class ElementListTest extends FunctionalTestCase
{
    /**
     * @var UserListPage
     */
    private $page;

    public function setUp()
    {
        parent::setUp();

        $this->createUser('l3l0');
        $this->createUser('otherUser');

        $client = self::createClient();
        $this->page = new UserListPage($client);
    }

    public function test_that_list_of_elements_is_shown()
    {
        $this->page->open('GET');
        $this->page->shouldHaveElementsOnTheList(2);
    }

    public function test_adding_new_element()
    {
        $this->page->open('GET');
        $listPage = $this
            ->page
            ->openAddNewElementForm()
            ->fillForm('l3l086')
            ->pressSubmitButton()
        ;

        $listPage->shouldHaveElementsOnTheList(3);
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