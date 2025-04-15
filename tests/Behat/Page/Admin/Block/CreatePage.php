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

use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsErrorTrait;
use Tests\Sylius\CmsPlugin\Behat\Helpers\ContentElementHelper;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function __construct(
        Session $session,
        $minkParameters,
        RouterInterface $router,
        string $routeName,
        protected AutocompleteHelperInterface $autocompleteHelper,
    ) {
        parent::__construct($session, $minkParameters, $router, $routeName);
    }

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

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

    public function associateCollections(array $collectionsNames): void
    {
        $collectionElement = $this->getElement('association_dropdown_collection');

        foreach ($collectionsNames as $collectionName) {
            $this->autocompleteHelper->selectByName(
                $this->getDriver(),
                $collectionElement->getXpath(),
                $collectionName,
            );
        }
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

        $select = $this->getLastContentElementSelect();
        $select->selectOption($contentElement);
        $select->waitFor(1, function () use ($contentElement): bool {
            return $this->hasElement(
                ContentElementHelper::getDefinedElementThatShouldAppearAfterSelectContentElement($contentElement),
            );
        });
    }

    private function getLastContentElementSelect()
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        $this->waitForFormUpdate();

        $elements = $this->getDocument()->findAll('css', '[data-test-content-element-type]');

        Assert::notEmpty($elements, 'No content element selects found.');

        foreach ($elements as $index => $el) {
            echo sprintf("Element[%d] name: %s\n", $index, $el->getAttribute('name'));
        }

        return end($elements);
    }

    public function addTextareaContentElementWithContent(string $content): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        $iframe = $this->getDocument()->find('css', '.cke_wysiwyg_frame');
        if (null === $iframe) {
            $textarea = $this->getElement('content_elements_textarea');
            $textarea->setValue($content);

            return;
        }

        $this->getDriver()->switchToIFrame($iframe->getAttribute('name'));

        $body = $this->getDocument()->find('css', 'body');
        if (null === $body) {
            throw new \Exception('CKEditor body not found');
        }

        $body->setValue($content);

        $this->getDriver()->switchToIFrame();
    }

    public function addSingleMediaContentElementWithName(string $name): void
    {
        $dropdown = $this->getElement('content_elements_single_media_dropdown');
        $dropdown->click();

        $dropdown->waitFor(5, function () use ($name): bool {
            return $this->hasElement('content_elements_single_media_dropdown_item', [
                '%item%' => $name,
            ]);
        });

        $item = $this->getElement('content_elements_single_media_dropdown_item', [
            '%item%' => $name,
        ]);

        $item->click();
    }

    public function addMultipleMediaContentElementWithNames(array $mediaNames): void
    {
        $dropdown = $this->getElement('content_elements_multiple_media_dropdown');
        $dropdown->click();

        foreach ($mediaNames as $mediaName) {
            $dropdown->waitFor(5, function () use ($mediaName): bool {
                return $this->hasElement('content_elements_multiple_media_dropdown_item', [
                    '%item%' => $mediaName,
                ]);
            });

            $item = $this->getElement('content_elements_multiple_media_dropdown_item', [
                '%item%' => $mediaName,
            ]);

            $item->click();
        }
    }

    public function addHeadingContentElementWithTypeAndContent(string $type, string $content): void
    {
        $heading = $this->getElement('content_elements_heading');
        $heading->selectOption($type);

        $headingContent = $this->getElement('content_elements_heading_content');
        $headingContent->setValue($content);
    }

    public function addProductsCarouselContentElementWithProducts(array $productsNames): void
    {
        $dropdown = $this->getElement('content_elements_products_carousel');
        $dropdown->click();

        foreach ($productsNames as $productName) {
            $dropdown->waitFor(5, function () use ($productName): bool {
                return $this->hasElement('content_elements_products_carousel_item', [
                    '%item%' => $productName,
                ]);
            });

            $item = $this->getElement('content_elements_products_carousel_item', [
                '%item%' => $productName,
            ]);

            $item->click();
        }
    }

    public function addProductsCarouselByTaxonContentElementWithTaxon(string $taxon): void
    {
        $dropdown = $this->getElement('content_elements_products_carousel_by_taxon');
        $dropdown->click();

        $dropdown->waitFor(5, function () use ($taxon): bool {
            return $this->hasElement('content_elements_products_carousel_by_taxon_item', [
                '%item%' => $taxon,
            ]);
        });

        $item = $this->getElement('content_elements_products_carousel_by_taxon_item', [
            '%item%' => $taxon,
        ]);

        $item->click();
    }

    public function addProductsGridContentElementWithProducts(array $productsNames): void
    {
        $dropdown = $this->getElement('content_elements_products_grid');
        $dropdown->click();

        foreach ($productsNames as $productName) {
            $dropdown->waitFor(5, function () use ($productName): bool {
                return $this->hasElement('content_elements_products_grid_item', [
                    '%item%' => $productName,
                ]);
            });

            $item = $this->getElement('content_elements_products_grid_item', [
                '%item%' => $productName,
            ]);

            $item->click();
        }
    }

    public function addProductsGridByTaxonContentElementWithTaxon(string $taxon): void
    {
        $dropdown = $this->getElement('content_elements_products_grid_by_taxon');
        $dropdown->click();

        $dropdown->waitFor(5, function () use ($taxon): bool {
            return $this->hasElement('content_elements_products_grid_by_taxon_item', [
                '%item%' => $taxon,
            ]);
        });

        $item = $this->getElement('content_elements_products_grid_by_taxon_item', [
            '%item%' => $taxon,
        ]);

        $item->click();
    }

    public function addTaxonsListContentElementWithTaxons(array $taxons): void
    {
        $dropdown = $this->getElement('content_elements_taxons_list');
        $dropdown->click();

        foreach ($taxons as $taxon) {
            $dropdown->waitFor(5, function () use ($taxon): bool {
                return $this->hasElement('content_elements_taxons_list_item', [
                    '%item%' => $taxon,
                ]);
            });

            $item = $this->getElement('content_elements_taxons_list_item', [
                '%item%' => $taxon,
            ]);

            $item->click();
        }
    }

    public function selectContentTemplate(string $templateName): void
    {
        $autocompleteElement = $this->getElement('content_template_select_dropdown');

        $this->autocompleteHelper->selectByName(
            $this->getDriver(),
            $autocompleteElement->getXpath(),
            $templateName,
        );
    }

    public function confirmUseTemplate(): void
    {
        $this->getDocument()->findById('load-template-confirmation-button')->click();
        $this->getDocument()->waitFor(1, function () {
            return false;
        });
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            ContentElementHelper::getDefinedContentElements(),
            [
                'association_dropdown_collection' => '[data-test-collection-autocomplete]',
                'content_elements_add_button' => '[data-test-add-content-element]',
                'content_elements_select_type' => '[data-test-content-element-type]',
                'content_template_select_dropdown' => '[data-test-content-template-autocomplete]',
                'content_template_select_dropdown_item' => '[data-test="content-template-autocomplete"] .menu .item:contains("%item%")',
            ],
        );
    }
}
