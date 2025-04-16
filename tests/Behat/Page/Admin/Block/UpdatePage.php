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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Block;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ChecksCodeImmutabilityTrait;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsContentElementTrait;
use Tests\Sylius\CmsPlugin\Behat\Helpers\ContentElementHelper;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutabilityTrait;
    use ContainsContentElementTrait;

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function fillNameIfItIsEmpty(string $name): void
    {
        if (empty($this->getDocument()->findField('Name')->getValue())) {
            $this->fillName($name);
        }
    }

    public function fillLink(string $link): void
    {
        $this->getDocument()->fillField('Link', $link);
    }

    public function fillContent(string $content): void
    {
        $this->getDocument()->fillField('Content', $content);
    }

    public function disable(): void
    {
        $this->getDocument()->uncheckField('Enabled');
    }

    public function isBlockDisabled(): bool
    {
        return $this->getDocument()->findField('Enabled')->isChecked();
    }

    public function changeTextareaContentElementValue(string $value): void
    {
        $this->getDocument()->fillField('Textarea', $value);
    }

    public function containsTextareaContentElementWithValue(string $value): bool
    {
        return $this->getDocument()->findField('Textarea')->getValue() === $value;
    }

    public function deleteContentElement(): void
    {
        $deleteButton = $this->getElement('delete_content_element_button');

        if (null === $deleteButton) {
            throw new \RuntimeException('Delete button for content element not found.');
        }

        $deleteButton->click();
        $this->waitForFormUpdate();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            ContentElementHelper::getDefinedContentElements(),
            [
                'content_elements_select' => '[data-test-content-elements]',
                'delete_content_element_button' => '[data-test-delete-content-element]',
            ],
        );
    }
}
