<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Translation\TranslatorInterface;

final class LocaleMenuExtension implements MenuExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     * @param array $locales
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, array $locales)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
    }

    /**
     * @param Item[] $menu
     * @return Item[]
     */
    public function extendMenu(array $menu) : array
    {
        return $this->addLocaleMenu($menu);
    }

    private function addLocaleMenu(array $menu) : array
    {
        if (count($this->locales) < 2) {
            return $menu;
        }

        $language = new Item('admin-locale');
        $language->setLabel(
            $this->translator->trans(
                'admin.language.current',
                ['%locale%' => $this->getLanguageName()],
                'AdminPanelBundle'
            )
        );
        $language->setOptions(['attr' => ['id' => 'language']]);

        foreach ($this->locales as $locale) {
            $localeItem = new RoutableItem(
                sprintf('admin-locale.%s', $locale),
                'admin_panel_locale',
                [
                    '_locale' => $locale,
                    'redirect_uri' => $this->requestStack->getMasterRequest()->getUri()
                ]
            );

            $localeItem->setLabel($this->getLanguageName($locale));
            if ($locale === $this->getCurrentLocale()) {
                $localeItem->setOptions(['attr' => ['class' => 'active']]);
            }
            $language->addChild($localeItem);
        }

        $menu[] = $language;

        return $menu;
    }

    private function getLanguageName(string $locale = null) : string
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }

        return Intl::getLanguageBundle()
            ->getLanguageName(
                $locale,
                null,
                $this->getCurrentLocale()
            )
        ;
    }

    /**
     * @return string
     */
    private function getCurrentLocale() : string
    {
        return $this->requestStack->getMasterRequest()->getLocale();
    }
}