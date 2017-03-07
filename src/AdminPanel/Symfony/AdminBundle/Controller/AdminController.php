<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AdminController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $indexActionTemplate;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param string $indexActionTemplate
     */
    public function __construct(EngineInterface $templating, RouterInterface $router, $indexActionTemplate)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->indexActionTemplate = $indexActionTemplate;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->templating->renderResponse($this->indexActionTemplate);
    }

    /**
     * @param string $_locale
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function localeAction($_locale, Request $request)
    {
        $request->getSession()->set('admin_locale', $_locale);

        return new RedirectResponse(
            $request->query->has('redirect_uri') ?
                $request->query->get('redirect_uri') :
                $this->router->generate('admin_panel_index')
        );
    }
}
