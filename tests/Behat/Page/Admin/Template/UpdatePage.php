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

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function hasContentElement(string $contentElement): bool
    {
        $selects = $this->getDocument()->findAll('css', 'select');
        foreach ($selects as $select) {
            $selectedOptionElement = $select->find('css', 'option[selected]');
            if (null !== $selectedOptionElement && $selectedOptionElement->getText() === $contentElement) {
                return true;
            }
        }

        return false;
    }

    public function hasOnlyContentElement(string $contentElement): bool
    {
        $selects = $this->getDocument()->findAll('css', 'select');
        $contentElementsCount = 0;
        foreach ($selects as $select) {
            $selectedOptionElement = $select->find('css', 'option[selected]');
            if (null !== $selectedOptionElement && $selectedOptionElement->getText() === $contentElement) {
                ++$contentElementsCount;
            }
        }

        return 1 === $contentElementsCount;
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function deleteContentElement(string $name): void
    {
        $contentElementSelect = $this->getDocument()->find('css', sprintf('option:contains("%s")', $name));
        $ancestorXpath = $contentElementSelect->getXpath() . '/ancestor::*[@data-test-content_element][1]';
        /** @var NodeElement|null $container */
        $container = $this->getDocument()->find('xpath', $ancestorXpath);
        $deleteButton = $container->find('css', '[data-test-delete-element]');

        $deleteButton->click();
    }
}
