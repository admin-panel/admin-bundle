<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

class NewUserPage extends BasePage
{
    private $form;

    /**
     * @return string;
     */
    public function getUrl() : string
    {
        return '/form/admin_users';
    }

    /**
     * @param string $username
     * @return Page
     */
    public function fillForm(string $username) : NewUserPage
    {
        $this->form = $this->getCrawler()->filter("form[name=\"form\"]")->form([
            'form[username]' => $username,
        ], 'POST');

        return $this;
    }

    /**
     * @return Page
     */
    public function pressSubmitButton() : Page
    {
        $this->client->submit($this->form);

        $status = $this->client->getResponse()->getStatusCode();

        $listPage = new UserListPage($this->client, $this);
        if ($status === 302 && $listPage->getUrl() === $this->client->getResponse()->getTargetUrl()) {
            return $listPage->open('GET');
        }

        return $this;
    }
}