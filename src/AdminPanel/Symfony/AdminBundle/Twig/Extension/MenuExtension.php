<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Twig\Extension;

use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;
use AdminPanel\Symfony\AdminBundle\Menu\MenuHelper;

class MenuExtension extends \Twig_Extension
{
    /**
     * @var MenuBuilder
     */
    private $menuBuilder;

    /**
     * @var MenuBuilder
     */
    private $toolsMenuBuilder;

    /**
     * @var MenuHelper
     */
    private $menuHelper;

    /**
     * @param MenuBuilder $menuBuilder
     * @param MenuBuilder $toolsMenuBuilder
     * @param MenuHelper $menuHelper
     */
    public function __construct(
        MenuBuilder $menuBuilder,
        MenuBuilder $toolsMenuBuilder,
        MenuHelper $menuHelper
    ) {
        $this->menuBuilder = $menuBuilder;
        $this->toolsMenuBuilder = $toolsMenuBuilder;
        $this->menuHelper = $menuHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'admin_menu';
    }

    /**
     * @param \Twig_Environment $environment
     * @return string
     */
    public function renderMenu(\Twig_Environment $environment) : string
    {
        $item = $this->menuBuilder->build();

        return $environment->render('@AdminPanel/Admin/menu.html.twig', ['root' => $item, 'menuHelper' => $this->menuHelper]);
    }

    /**
     * @param \Twig_Environment $environment
     * @return string
     */
    public function renderToolsMenu(\Twig_Environment $environment) : string
    {
        $item = $this->toolsMenuBuilder->build();

        return $environment->render('@AdminPanel/Admin/menu.html.twig', ['root' => $item, 'menuHelper' => $this->menuHelper]);
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
             new \Twig_SimpleFunction(
                'admin_panel_render_menu',
                [$this, 'renderMenu'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new \Twig_SimpleFunction(
                'admin_panel_render_tools_menu',
                [$this, 'renderToolsMenu'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            )
        ];
    }
}
