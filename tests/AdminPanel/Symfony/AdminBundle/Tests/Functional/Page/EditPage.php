<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

use Symfony\Bundle\FrameworkBundle\Client;

class EditPage extends BasePage
{
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
        return sprintf('/list/%s?id={id}', $this->pageName);
    }
}