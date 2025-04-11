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

use DMore\ChromeDriver\ChromeDriver;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsErrorTrait;
use Tests\Sylius\CmsPlugin\Behat\Helpers\ContentElementHelper;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function chooseType(string $name): void
    {
        $this->getDocument()->selectFieldOption('Type', $name);
    }

    public function clickOnAddContentElementButton(): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        $addButton = $this->getElement('content_elements_add_button');
        $addButton->click();

        $addButton->waitFor(1, function (): bool {
            return $this->hasElement('content_elements_select_type');
        });
    }

    public function selectContentElement(string $contentElement): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);
        $this->waitForFormUpdate();

        $allSelects = $this->getDocument()->findAll('css', '[data-test-content-element-type]');
        $select = end($allSelects);
        $select->selectOption($contentElement);
        $select->waitFor(1, function () use ($contentElement): bool {
            return $this->hasElement(
                ContentElementHelper::getDefinedElementThatShouldAppearAfterSelectContentElement($contentElement),
            );
        });
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            ContentElementHelper::getDefinedContentElements(),
            [
                'form' => 'form[name="sylius_cms_template"]',
                'content_elements_add_button' => '[data-test-add-content-element]',
            ],
        );
    }

    protected function waitForFormUpdate(): void
    {
        $form = $this->getElement('form');
        usleep(500000);
        $form->waitFor(1500, fn () => !$form->hasAttribute('busy'));
    }
}
