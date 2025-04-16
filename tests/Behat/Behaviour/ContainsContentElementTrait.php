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

use Sylius\Behat\Behaviour\DocumentAccessor;

trait ContainsContentElementTrait
{
    use DocumentAccessor;

    public function containsContentElement(string $expectedLabel): bool
    {
        $contentElementsContainer = $this->getElement('content_elements_select');

        if (null === $contentElementsContainer) {
            throw new \InvalidArgumentException('Content elements container not found.');
        }

        $selects = $contentElementsContainer->findAll('css', '[data-test-content-element-type]');

        foreach ($selects as $select) {
            $selectedValue = $select->getValue();
            $selectedOption = $select->find('css', sprintf('option[value="%s"]', $selectedValue));

            if (null === $selectedOption) {
                continue;
            }

            $label = trim($selectedOption->getText());

            if ($label === $expectedLabel) {
                return true;
            }
        }

        return false;
    }
}
