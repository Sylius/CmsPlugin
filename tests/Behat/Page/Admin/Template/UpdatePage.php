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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Template;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Webmozart\Assert\Assert;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function hasContentElement(string $contentElement): bool
    {
        return \in_array($contentElement, $this->getContentElements(), true);
    }

    public function hasOnlyContentElement(string $contentElement): bool
    {
        return [$contentElement] === $this->getContentElements();
    }

    public function getContentElements(): array
    {
        return array_map(
            static fn (NodeElement $label): string => trim($label->getText()),
            $this->getDocument()->findAll('css', '[data-test-content-element-label]'),
        );
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function deleteContentElement(string $name): void
    {
        $this->clickContentElementAction($name, 'delete-element');
    }

    public function moveContentElement(string $name, string $direction): void
    {
        $this->clickContentElementAction($name, 'move-' . $direction);
    }

    private function clickContentElementAction(string $name, string $action): void
    {
        $elements = $this->getContentElements();
        $element = $this->getDocument()->find('xpath', sprintf(
            '//*[@data-test-content-element][.//*[@data-test-content-element-label][normalize-space(.) = %s]]',
            $this->getSession()->getSelectorsHandler()->xpathLiteral($name),
        ));
        Assert::notNull($element, sprintf('There is no "%s" content element.', $name));

        $button = $element->find('css', sprintf('[data-test-%s]', $action));
        Assert::notNull($button, sprintf('There is no "%s" action for the "%s" content element.', $action, $name));
        Assert::false($button->hasAttribute('disabled'), sprintf('The "%s" action for the "%s" content element is disabled.', $action, $name));
        $button->click();

        Assert::true(
            $this->getDocument()->waitFor(5, fn (): bool => $this->getContentElements() !== $elements),
            sprintf('The content elements did not change after the "%s" action for the "%s" content element.', $action, $name),
        );
        $this->waitForFormUpdate();
    }
}
