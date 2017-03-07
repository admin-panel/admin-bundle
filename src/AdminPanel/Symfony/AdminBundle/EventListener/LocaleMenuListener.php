<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleMenuListener
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
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param array $locales
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, array $locales)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
    }

    public function createLocaleMenu(MenuEvent $event)
    {
        if (count($this->locales) < 2) {
            return;
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

        $event->getMenu()->addChild($language);
    }

    private function getLanguageName($locale = null)
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }

        return Intl::getLanguageBundle()
            ->getLanguageName(
                $locale,
                null,
                $this->getCurrentLocale()
            );
    }

    /**
     * @return string
     */
    private function getCurrentLocale()
    {
        return $this->requestStack->getMasterRequest()->getLocale();
    }
}
