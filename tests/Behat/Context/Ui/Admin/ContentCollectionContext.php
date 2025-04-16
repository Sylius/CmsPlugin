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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\CmsPlugin\Form\Type\ContentElements\HeadingContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\SingleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TaxonsListContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TextareaContentElementType;
use Tests\Sylius\CmsPlugin\Behat\Element\Admin\ContentElementsCollectionElementInterface;
use Webmozart\Assert\Assert;

class ContentCollectionContext implements Context
{
    public function __construct(
        private ContentElementsCollectionElementInterface $contentElementsCollectionElement,
    ) {
    }

    /**
     * @When I add a textarea content element with :content content
     */
    public function iAddATextareaContentElementWithContent(string $content): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            TextareaContentElementType::TYPE,
            $content,
        );
    }

    /**
     * @When I add a single media content element with name :name
     */
    public function iAddASingleMediaContentElementWithName(string $name): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            SingleMediaContentElementType::TYPE,
            $name,
        );
    }

    /**
     * @When I add a multiple media content element with names :firstMediaName and :secondMediaName
     */
    public function iAddAMultipleMediaContentElementWithNames(string ...$mediaNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            MultipleMediaContentElementType::TYPE,
            $mediaNames,
        );
    }

    /**
     * @When I add a heading content element with type :type and :content content
     */
    public function iAddAHeadingContentElementWithTypeAndContent(string $type, string $content): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            HeadingContentElementType::TYPE,
            [
                'heading_type' => $type,
                'heading' => $content,
            ],
        );
    }

    /**
     * @When I add a products carousel content element with :firstProductName and :secondProductName products
     */
    public function iAddAProductsCarouselContentElementWithProducts(string ...$productsNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsCarouselContentElementType::TYPE,
            $productsNames,
        );
    }

    /**
     * @When I add a products carousel by taxon content element with :taxon taxonomy
     */
    public function iAddAProductsCarouselByTaxonContentElementWithTaxon(string $taxon): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsCarouselByTaxonContentElementType::TYPE,
            $taxon,
        );
    }

    /**
     * @When I add a products grid content element with :firstProductName and :secondProductName products
     */
    public function iAddAProductsGridContentElementWithProducts(string ...$productsNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsGridContentElementType::TYPE,
            $productsNames,
        );
    }

    /**
     * @When I add a products grid by taxon content element with :taxon taxonomy
     */
    public function iAddAProductsGridByTaxonContentElementWithTaxon(string $taxon): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsGridByTaxonContentElementType::TYPE,
            $taxon,
        );
    }

    /**
     * @When I add a taxons list content element with :firstTaxon and :secondTaxon taxonomy
     */
    public function iAddATaxonsListContentElementWithTaxons(string ...$taxons): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            TaxonsListContentElementType::TYPE,
            $taxons,
        );
    }

    /**
     * @When I change textarea content element value to :value
     */
    public function iChangeTextareaContentElementValueTo(string $value): void
    {
        $this->contentElementsCollectionElement->updateContentElementOfType(
            TextareaContentElementType::TYPE,
            $value,
        );
    }

    /**
     * @When I select :templateName content template
     */
    public function iSelectContentTemplate(string $templateName): void
    {
        $this->contentElementsCollectionElement->selectTemplate($templateName);
    }

    /**
     * @When I confirm that I want to use this template
     */
    public function iConfirmThatIWantToUseThisTemplate(): void
    {
        $this->contentElementsCollectionElement->applyTemplate();
    }

    /**
     * @Then I should see :content in the textarea content element
     */
    public function iShouldSeeNewContentInTheTextareaContentElement(string $content): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElementWithContent(
            TextareaContentElementType::TYPE,
            $content,
        ));
    }

    /**
     * @Then I should see newly created :contentElement content element in Content elements section
     */
    public function iShouldSeeNewlyCreatedContentElementInContentElementsSection(string $contentElement): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElement($contentElement));
    }

    /**
     * @Then I should see a :type element with :content content
     * @Then I should see a :type element with :firstContent and :secondContent content
     */
    public function iShouldSeeATypeElementWithContent(string $type, string ...$content): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElementWithContent($type, $content));
    }

    /**
     * @When I delete the :contentElement content element
     */
    public function iDeleteTheContentElement(string $contentElement): void
    {
        $this->contentElementsCollectionElement->removeContentElement($contentElement);
    }

    /**
     * @Then I should not see :contentElement content element in the Content elements section
     */
    public function iShouldNotSeeContentElementInTheContentElementsSection(string $contentElement): void
    {
        Assert::false($this->contentElementsCollectionElement->hasContentElement($contentElement));
    }
}
