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

namespace Tests\Sylius\CmsPlugin\Behat\Element\Admin;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Sylius\Behat\Element\Admin\Crud\FormElement;
use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\HeadingContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\PagesCollectionContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\SingleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\SpacerContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TaxonsListContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TextareaContentElementType;
use Webmozart\Assert\Assert;

class ContentElementsCollectionElement extends FormElement implements ContentElementsCollectionElementInterface
{
    public const CONTENT_TYPE_AUTOCOMPLETE = [
        MultipleMediaContentElementType::TYPE,
        PagesCollectionContentElementType::TYPE,
        ProductsCarouselByTaxonContentElementType::TYPE,
        ProductsCarouselContentElementType::TYPE,
        ProductsGridByTaxonContentElementType::TYPE,
        ProductsGridContentElementType::TYPE,
        SingleMediaContentElementType::TYPE,
        TaxonsListContentElementType::TYPE,
    ];

    public const CONTENT_TYPE_SIMPLE = [
        SpacerContentElementType::TYPE,
        TextareaContentElementType::TYPE,
    ];

    public const CONTENT_TYPE_COMPOUND = [
        HeadingContentElementType::TYPE,
    ];

    /** @param MinkParameters|array<array-key, mixed> $minkParameters */
    public function __construct(
        Session $session,
        array|MinkParameters $minkParameters,
        protected readonly AutocompleteHelperInterface $autocompleteHelper,
        protected readonly string $defaultLocaleCode = 'en_US',
    ) {
        parent::__construct($session, $minkParameters);
    }

    public function selectTemplate(string $templateName): void
    {
        $select = $this->getElement('elements_template_select', ['%locale%' => $this->defaultLocaleCode]);

        $this->autocompleteHelper->search(
            $this->getDriver(),
            $select->getXpath(),
            'home',
        );
        $this->autocompleteHelper->selectByName(
            $this->getDriver(),
            $select->getXpath(),
            $templateName,
        );

        $this->waitForFormUpdate();
    }

    public function applyTemplate(): void
    {
        $this->getElement('elements_template_apply', ['%locale%' => $this->defaultLocaleCode])->click();

        $this->waitForFormUpdate();
    }

    public function hasContentElement(string $type): bool
    {
        try {
            $this->getContentElementByTypeLabel($type);

            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    public function hasContentElementWithContent(string $type, array|string $content): bool
    {
        $element = $this->getContentElementByTypeLabel($type);

        $content = is_string($content) ? [$content] : $content;

        if ($this->isAutocompleteContent($type)) {
            $autocompleteXpath = $this->getAutocompleteXpath($element);
            $selectedItems = $this->autocompleteHelper->getSelectedItems($this->getDriver(), $autocompleteXpath);

            foreach ($selectedItems as $key => $value) {
                foreach ($content as $item) {
                    if (false !== stripos($key, $item) || false !== stripos($value, $item)) {
                        continue 2;
                    }
                }

                return false;
            }

            return true;
        }

        $htmlContent = $element->getHtml();

        foreach ($content as $item) {
            if (!str_contains($htmlContent, $item)) {
                return false;
            }
        }

        return true;
    }

    public function getContentElementsCount(): int
    {
        return count($this->getContentElements());
    }

    public function updateContentElementOfType(string $type, array|string $content): void
    {
        $this->setContentOnElementOfType($this->getContentElementByType($type), $type, $content);
    }

    public function addContentElementOfTypeWithContent(string $type, array|string $content): void
    {
        $this->setContentOnElementOfType($this->addContentElement($type), $type, $content);
    }

    public function removeContentElement(string $type): void
    {
        $element = $this->getContentElementByTypeLabel($type);

        $element->find('css', '[data-test-delete-action]')?->click();

        $this->waitForFormUpdate();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'form' => '[data-live-name-value^="sylius_cms:admin"][data-live-name-value$=":form"]',
            'elements_container' => '#translation-elements-%locale% [data-test-elements-collection]',
            'elements_add' => '#translation-elements-%locale% [data-test-add-element-button]',
            'elements_template_select' => '#translation-elements-%locale% [data-test-content-template]',
            'elements_template_apply' => '#translation-elements-%locale% [data-test-apply-template-button]',
        ]);
    }

    /** @param string|array<array-key|string, string> $content */
    protected function setContentOnElementOfType(NodeElement $element, string $type, array|string $content): void
    {
        if ($this->isSimpleContent($type)) {
            Assert::string($content, 'Content should be a string for simple content elements.');
            $this->setSimpleComponentContent($element, $content);

            return;
        }

        if ($this->isAutocompleteContent($type)) {
            $this->setAutocompleteComponentContent($element, $content);

            return;
        }

        if ($this->isCompoundContent($type)) {
            Assert::isArray($content, 'Content should be an array for compound content elements.');
            $this->setCompoundComponentContent($element, $content);

            return;
        }

        throw new \InvalidArgumentException(sprintf('Content element "%s" not supported.', $type));
    }

    protected function addContentElement(string $type): NodeElement
    {
        $addElementDropdown = $this->getElement('elements_add', ['%locale%' => $this->defaultLocaleCode]);
        $addElementDropdown->click();

        $addButton = $addElementDropdown->find('css', sprintf('button[data-live-type-param="%s"]', $type));
        Assert::isInstanceOf(
            $addButton,
            NodeElement::class,
            sprintf('Add button for content element "%s" not found.', $type),
        );

        $addButton->click();

        $this->waitForFormUpdate();

        return $this->getLastContentElement();
    }

    protected function getContentElementByTypeLabel(string $typeLabel): NodeElement
    {
        return $this->getContentElementBySelector(sprintf('option[selected]:contains("%s")', $typeLabel));
    }

    protected function getContentElementByType(string $type): NodeElement
    {
        return $this->getContentElementBySelector(sprintf('option[selected][value="%s"]', $type));
    }

    protected function getContentElementBySelector(string $selector): NodeElement
    {
        foreach ($this->getContentElements() as $item) {
            $foundElement = $item->find('css', $selector);
            if (!$foundElement instanceof NodeElement) {
                continue;
            }

            return $item;
        }

        throw new \InvalidArgumentException(sprintf('Content element with selector "%s" not found.', $selector));
    }

    protected function getLastContentElement(): NodeElement
    {
        $elements = $this->getContentElements();

        /** @phpstan-ignore-next-line */
        return end($elements);
    }

    /** @return NodeElement[] */
    protected function getContentElements(): array
    {
        $container = $this->getElement('elements_container', ['%locale%' => $this->defaultLocaleCode]);
        $elements = $container->findAll('css', '[data-test-entry-row]');
        Assert::notEmpty($elements, 'Content elements not found.');

        return $elements;
    }

    protected function isSimpleContent(string $type): bool
    {
        return in_array($type, self::CONTENT_TYPE_SIMPLE, true);
    }

    protected function setSimpleComponentContent(NodeElement $element, string $content): void
    {
        $input = $element->find('css', 'input') ?? $element->find('css', 'textarea');
        Assert::isInstanceOf($input, NodeElement::class, 'Input element not found.');

        $input->setValue($content);

        $this->waitForFormUpdate();
    }

    protected function isCompoundContent(string $type): bool
    {
        return in_array($type, self::CONTENT_TYPE_COMPOUND, true);
    }

    /** @param array<string, string> $content */
    protected function setCompoundComponentContent(NodeElement $element, array $content): void
    {
        foreach ($content as $key => $item) {
            $fieldElement = $element->find('css', sprintf('div.field [name$="[%s]"]', $key));
            Assert::isInstanceOf(
                $fieldElement,
                NodeElement::class,
                sprintf('Compound input element with key "%s" not found.', $key),
            );

            if ($fieldElement->has('css', 'select')) {
                $fieldElement->selectOption($item);
            } else {
                $fieldElement->setValue($item);
            }
        }

        $this->waitForFormUpdate();
    }

    protected function isAutocompleteContent(string $type): bool
    {
        return in_array($type, self::CONTENT_TYPE_AUTOCOMPLETE, true);
    }

    /** @param string|string[] $content */
    protected function setAutocompleteComponentContent(NodeElement $element, array|string $content): void
    {
        $content = is_string($content) ? [$content] : $content;

        $autocompleteElementXpath = $this->getAutocompleteXpath($element);

        foreach ($content as $item) {
            $this->autocompleteHelper->selectByName(
                $this->getDriver(),
                $autocompleteElementXpath,
                $item,
            );

            $this->waitForFormUpdate();
        }
    }

    private function getAutocompleteXpath(NodeElement $element): string
    {
        $autocompleteElement = $element->find('css', 'select[data-controller*="autocomplete"]');
        Assert::isInstanceOf($autocompleteElement, NodeElement::class, 'Autocomplete element not found.');

        return $autocompleteElement->getXpath();
    }
}
