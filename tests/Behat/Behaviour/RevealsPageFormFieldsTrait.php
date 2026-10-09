<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\CmsPlugin\Behat\Behaviour;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Sylius\Behat\Service\DriverHelper;

/**
 * @method Session getSession()
 * @method DocumentElement getDocument()
 */
trait RevealsPageFormFieldsTrait
{
    protected function revealElement(NodeElement $element): void
    {
        if (!DriverHelper::isJavascript($this->getSession()->getDriver())) {
            return;
        }

        $tabPane = $element->find('xpath', 'ancestor-or-self::*[@role="tabpanel"][@id][1]');
        if (null !== $tabPane) {
            $this->getDocument()->find('css', sprintf('[data-bs-toggle="tab"][data-bs-target="#%s"]', $tabPane->getAttribute('id')))?->click();
        }

        $localePane = $element->find('xpath', 'ancestor-or-self::*[@data-ui-pane="activeLocale"][1]');
        $locale = $localePane?->getAttribute('data-ui-pane-value');
        if (null !== $locale) {
            $this->getDocument()->find('css', sprintf('[data-test-page-locale="%s"]', $locale))?->click();
        }

        $collapse = $element->find('xpath', 'ancestor-or-self::*[contains(concat(" ", normalize-space(@class), " "), " collapse ")][not(contains(concat(" ", normalize-space(@class), " "), " show "))][1]');
        if (null !== $collapse) {
            $this->getDocument()->find('css', sprintf('[data-bs-target="#%s"]', $collapse->getAttribute('id')))?->click();
        }

        $elementPane = $element->find('xpath', 'ancestor-or-self::*[starts-with(@data-ui-pane, "activeElements.")][1]');
        if (null !== $elementPane && null !== $locale) {
            $this->getDocument()->find('css', sprintf(
                '#translation-elements-%s [data-test-structure-row="%s"]',
                $locale,
                $elementPane->getAttribute('data-ui-pane-value'),
            ))?->click();
        }
    }

    protected function revealField(string $locator): void
    {
        $field = $this->getDocument()->findField($locator);
        if (null !== $field) {
            $this->revealElement($field);
        }
    }
}
