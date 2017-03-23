<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class BasePage implements Page
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Page
     */
    private $previousPage;

    /**
     * @param Client $client
     * @param Page $previousPage
     */
    public function __construct(Client $client, Page $previousPage = null)
    {
        $this->client = $client;
        $this->previousPage = $previousPage;
    }

    /**
     * @param string $method
     * @param array $urlParameters
     * @param array $parameters
     * @return self|Page
     */
    public function open(string $method = 'GET', array $urlParameters = [], array $parameters = []) : Page
    {
        $this->client->request($method, $this->unmaskUrl($urlParameters), $parameters, []);

        if (!in_array($this->client->getResponse()->getStatusCode(), [200, 302])) {
            $this->printLastResponse();

            throw new \RuntimeException(sprintf("Can't open \"%s\"", $this->getUrl()));
        }

        return $this;
    }

    /**
     * @return Page
     */
    public function followRedirects() : Page
    {
        $this->client->followRedirects();

        return $this;
    }

    /**
     * @param string $url
     * @return Page
     */
    public function shouldBeRedirectedFrom(string $url) : Page
    {
        if (is_null($this->previousPage)) {
            throw new \RuntimeException(sprintf("Page \"%s\" was not open by redirection.", $this->getUrl()));
        }

        if ($this->previousPage->getUrl() !== $url) {
            throw new \RuntimeException(sprintf("Previous page url was \"%s\".", $this->previousPage->getUrl()));
        }

        return $this;
    }

    /**
     * @param string $url
     * @return Page
     */
    public function shouldBeRedirectedTo(string $url) : Page
    {
        if ($this->getUrl() !== $url) {
            throw new \RuntimeException(sprintf("Expected \"%s\", but current page url is \"%s\".", $url, $this->getUrl()));
        }

        return $this;
    }

    /**
     * Debug purpose only, do not use in tests!
     * @return Page
     */
    public function printLastResponse() : Page
    {
        $content = $this->client->getResponse()->getContent();

        echo "\n\033[36m|  " . strtr($content, ["\n" => "\n|  "]) . "\033[0m\n\n";

        return $this;
    }

    /**
     * @return Crawler
     */
    protected function getCrawler() : Crawler
    {
        if (!$this->client->getCrawler()) {
            throw new \Exception('Client not found, so Crawler cannot be returned');
        }
        return $this->client->getCrawler();
    }

    /**
     * @param array $urlParameters
     * @return string
     */
    private function unmaskUrl(array $urlParameters) : string
    {
        $url = $this->getUrl();
        foreach ($urlParameters as $parameter => $value) {
            $url = str_replace(sprintf('{%s}', $parameter), $value, $url);
        }

        return $url;
    }
}
