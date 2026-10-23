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
        $this->getDocument()->selectFieldOption('sylius_cms_template[type]', strtolower($name));
    }

    public function clickOnAddContentElementButton(): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        $dropdown = $this->getElement('content_elements_add_button');
        $toggle = $dropdown->find('css', '[data-bs-toggle="dropdown"]');
        Assert::notNull($toggle, 'There is no button opening the content element types.');
        $toggle->click();
        $dropdown->waitFor(5, static fn (): bool => null !== $dropdown->find('css', '.dropdown-menu.show'));
    }

    public function selectContentElement(string $contentElement): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        $count = \count($this->getDocument()->findAll('css', '[data-test-content-element]'));
        $option = $this->getElement('content_elements_add_button')->find('xpath', sprintf(
            '//button[@data-live-type-param][normalize-space(.) = %s]',
            $this->getSession()->getSelectorsHandler()->xpathLiteral($contentElement),
        ));
        Assert::notNull($option, sprintf('There is no "%s" content element type to add.', $contentElement));
        $option->click();

        Assert::true(
            $this->getDocument()->waitFor(5, fn (): bool => \count($this->getDocument()->findAll('css', '[data-test-content-element]')) > $count),
            sprintf('The "%s" content element has not been added.', $contentElement),
        );
        $this->waitForFormUpdate();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'content_elements_add_button' => '[data-test-template-elements] [data-test-add-element-button]',
                'form' => 'form[name="sylius_cms_template"]',
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
