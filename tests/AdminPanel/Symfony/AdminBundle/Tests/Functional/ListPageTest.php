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

        $this->createUser('l3l0', true, 10.30, 6);
        $this->createUser('otherUser', false, 5.35, 4);

        $this->client = self::createClient();
    }

    public function test_that_list_of_elements_is_shown()
    {
        (new ListPage($this->client))
            ->open()
            ->shouldHaveElementsOnTheList(2)
            ->shouldHaveButtonOnElementNumber('Edit', 1)
            ->shouldHaveButtonOnElementNumber('Edit', 2)
            ->shouldHaveButtonOnElementNumber('Display', 1)
            ->shouldHaveButtonOnElementNumber('Display', 2)
            ->shouldHaveButtonOnElementNumber('Custom', 1)
            ->shouldHaveButtonOnElementNumber('Custom', 2)
        ;
    }

    public function test_adding_new_element()
    {
        (new ListPage($this->client))
            ->open()
            ->shouldHaveAddNewElementButton()
            ->openAddNewElementForm()
            ->fillForm('l3l086')
            ->pressSubmitButton()
            ->shouldHaveElementsOnTheList(3)
        ;
    }

    public function test_that_can_go_to_edit_page()
    {
        (new ListPage($this->client))
            ->open()
            ->clickEditButtonForElementWithNumber(1)
            ->shouldBeRedirectedTo('/list/admin_users?id={id}')
        ;
    }

    public function test_that_can_go_to_custom_page()
    {
        (new ListPage($this->client))
            ->open()
            ->clickCustomButtonForElementWithNumber(1)
            ->shouldBeRedirectedTo('/custom-action?id={id}')
        ;
    }

    public function test_custom_list_template_for_element()
    {
        (new ListPage($this->client, 'admin_custom_template_users'))
            ->open()
            ->shouldHaveElementsOnTheList(2)
            ->shouldSeePageHeader('List of Users (custom template)')
        ;
    }

    /**
     * @param string $username
     * @param bool $hasNewsletter
     * @param float $credits
     * @param int $itemQuantity
     * @return User
     */
    private function createUser(
        string $username,
        bool $hasNewsletter = false,
        float $credits = 0.00,
        int $itemQuantity = 0
    ) : User {
        $user = new User();
        $user->username = $username;
        $user->hasNewsletter = $hasNewsletter;
        $user->credits = $credits;
        $user->itemQuantity = $itemQuantity;

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}