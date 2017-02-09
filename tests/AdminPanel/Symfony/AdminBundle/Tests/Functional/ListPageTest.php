<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Page\ListPage;

class ListPageTest extends FunctionalTestCase
{
    public function test_that_list_of_elements_is_shown()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->shouldHaveElementsOnTheList(2)
            ->shouldHaveButtonOnElementNumber('Edit', 1)
            ->shouldHaveButtonOnElementNumber('Edit', 2)
            ->shouldHaveButtonOnElementNumber('Display', 1)
            ->shouldHaveButtonOnElementNumber('Display', 2)
            ->shouldHaveButtonOnElementNumber('Custom', 1)
            ->shouldHaveButtonOnElementNumber('Custom', 2)
            ->shouldHaveAddNewElementButton()
            ->shouldHaveDeleteBatchAction()
        ;
    }

    public function test_adding_new_element()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

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
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->clickEditButtonForElementWithNumber(1)
            ->shouldBeRedirectedTo('/list/admin_users?id={id}')
        ;
    }

    public function test_that_can_go_to_custom_page()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->clickCustomButtonForElementWithNumber(1)
            ->shouldBeRedirectedTo('/custom-action?id={id}')
        ;
    }

    public function test_custom_list_template_for_element()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client, 'admin_custom_template_users'))
            ->open()
            ->shouldHaveElementsOnTheList(2)
            ->shouldSeePageHeader('List of Users (custom template)')
        ;
    }

    public function test_that_cannot_add_element_which_have_disabled_allow_add_option()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client, 'admin_custom_template_users'))
            ->open()
            ->shouldNotHaveAddNewElementButton()
        ;
    }

    public function test_that_cannot_delete_element_which_have_disabled_allow_delete_option()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client, 'admin_custom_template_users'))
            ->open()
            ->shouldNotHaveDeleteBatchAction()
        ;
    }

    public function test_that_list_of_elements_is_shown_for_dbal_driver()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client, 'admin_users_dbal'))
            ->open()
            ->shouldHaveElementsOnTheList(2)
            ->shouldHaveButtonOnElementNumber('Edit', 1)
            ->shouldHaveButtonOnElementNumber('Edit', 2)
            ->shouldHaveButtonOnElementNumber('Display', 1)
            ->shouldHaveButtonOnElementNumber('Display', 2)
            ->shouldHaveButtonOnElementNumber('Custom', 1)
            ->shouldHaveButtonOnElementNumber('Custom', 2)
            ->shouldHaveAddNewElementButton()
            ->shouldHaveDeleteBatchAction()
        ;
    }

    public function test_that_can_sort_list_using_text_type()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->sortBy('Username', 'DESC')
            ->shouldHaveElementOnTheListAtPosition('otherUser', 1)
            ->shouldHaveElementOnTheListAtPosition('l3l0', 2)
            ->sortBy('Username', 'ASC')
            ->shouldHaveElementOnTheListAtPosition('l3l0', 1)
            ->shouldHaveElementOnTheListAtPosition('otherUser', 2)
        ;
    }

    public function test_that_can_sort_list_using_datetime_type()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->sortBy('Created at', 'DESC')
            ->shouldHaveElementOnTheListAtPosition('otherUser', 1)
            ->shouldHaveElementOnTheListAtPosition('l3l0', 2)
            ->sortBy('Created at', 'ASC')
            ->shouldHaveElementOnTheListAtPosition('l3l0', 1)
            ->shouldHaveElementOnTheListAtPosition('otherUser', 2)
        ;
    }

    public function test_that_can_sort_list_using_number_type()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->sortBy('Credits', 'ASC')
            ->shouldHaveElementOnTheListAtPosition('otherUser', 1)
            ->shouldHaveElementOnTheListAtPosition('l3l0', 2)
            ->sortBy('Credits', 'DESC')
            ->shouldHaveElementOnTheListAtPosition('l3l0', 1)
            ->shouldHaveElementOnTheListAtPosition('otherUser', 2)
        ;
    }

    public function test_that_can_filter_result_by_different_types()
    {
        $this->dbContext->createUser('l3l0', true, 10.30, 6);
        $this->dbContext->createUser('otherUser', false, 5.35, 4, new \DateTime('+1 day'));

        (new ListPage($this->client))
            ->open()
            ->fillFilterForm([
                'Username' => 'l3l'
            ])
            ->pressSearchButton()
            ->shouldHaveElementsOnTheList(1)
            ->shouldHaveElementOnTheListAtPosition('l3l0', 1)
            ->fillFilterForm([
                'Credits' => 5.35
            ])
            ->pressSearchButton()
            ->shouldHaveElementsOnTheList(1)
            ->shouldHaveElementOnTheListAtPosition('otherUser', 1)
        ;
    }

    public function test_pagination()
    {
        $this->dbContext->createUsers(15);

        (new ListPage($this->client))
            ->open()
            ->shouldHavePages(2)
            ->shouldHaveElementsOnTheList(10)
            ->openPage(2)
            ->shouldHaveElementsOnTheList(5)
        ;
    }

    public function test_pagination_for_dbal_driver()
    {
        $this->dbContext->createUsers(15);

        (new ListPage($this->client, 'admin_users_dbal'))
            ->open()
            ->shouldHavePages(2)
            ->shouldHaveElementsOnTheList(10)
            ->openPage(2)
            ->shouldHaveElementsOnTheList(5)
        ;
    }

    public function test_change_list_elements_number()
    {
        $this->dbContext->createUsers(15);

        (new ListPage($this->client))
            ->open('GET', [], ['admin_users' => ['max_results' => 2]])
            ->shouldHavePages(8)
            ->shouldHaveElementsOnTheList(2)
            ->openPage(2)
            ->shouldHaveElementsOnTheList(2)
            ->openPage(8)
            ->shouldHaveElementsOnTheList(1)
        ;
    }

    public function test_change_list_elements_number_for_dbal_driver()
    {
        $this->dbContext->createUsers(15);

        (new ListPage($this->client, 'admin_users_dbal'))
            ->open('GET', [], ['admin_users_dbal' => ['max_results' => 2]])
            ->shouldHavePages(8)
            ->shouldHaveElementsOnTheList(2)
            ->openPage(2)
            ->shouldHaveElementsOnTheList(2)
            ->openPage(8)
            ->shouldHaveElementsOnTheList(1)
        ;
    }

    public function test_delete_batch_action()
    {
        $this->dbContext->createUser('l3l0');
        $this->dbContext->createUser('otherUser');
        $this->dbContext->createUser('niflheim');

        (new ListPage($this->client))
            ->open()
            ->shouldHaveElementsOnTheList(3)
            ->batchDeleteElements([1, 3])
            ->shouldHaveElementsOnTheList(1)
            ->shouldHaveElementOnTheListAtPosition('otherUser', 1)
        ;
    }
}