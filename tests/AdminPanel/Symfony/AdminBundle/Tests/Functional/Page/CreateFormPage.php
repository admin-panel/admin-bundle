<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Form;

class CreateFormPage extends BasePage
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var string
     */
    private $pageName;

    public function __construct(Client $client, $pageName = 'admin_users', Page $previousPage = null)
    {
        parent::__construct($client, $previousPage);

        $this->pageName = $pageName;
    }

    /**
     * @return string;
     */
    public function getUrl() : string
    {
        return '/form/' . $this->pageName;
    }

    /**
     * @param string $username
     * @return Page
     */
    public function fillForm(string $username) : CreateFormPage
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

        $listPage = new ListPage($this->client, $this->pageName, $this);
        if ($status === 302 && $listPage->getUrl() === $this->client->getResponse()->getTargetUrl()) {
            return $listPage->open();
        }

        return $this;
    }
}