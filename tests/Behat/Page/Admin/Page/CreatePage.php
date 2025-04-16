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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Page;

use Behat\Mink\Session;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Behat\Service\DriverHelper;
use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsErrorTrait;
use Tests\Sylius\CmsPlugin\Behat\Service\FormHelper;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function __construct(
        Session $session,
        array|MinkParameters $minkParameters,
        RouterInterface $router,
        string $routeName,
        private readonly AutocompleteHelperInterface $autocompleteHelper,
    ) {
        parent::__construct($session, $minkParameters, $router, $routeName);
    }

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function chooseImage(string $code): void
    {
        FormHelper::fillHiddenInput($this->getSession(), self::IMAGE_FORM_ID, $code);
    }

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function fillSlug(string $slug): void
    {
        $this->getDocument()->fillField('Slug', $slug);
    }

    public function fillMetaKeywords(string $metaKeywords): void
    {
        $this->getDocument()->fillField('Meta keywords', $metaKeywords);
    }

    public function fillMetaDescription(string $metaDescription): void
    {
        $this->getDocument()->fillField('Meta description', $metaDescription);
    }

    public function fillContent(string $content): void
    {
        $this->getDocument()->fillField('Content', $content);
    }

    public function getCollections(): array
    {
        Assert::true(DriverHelper::isJavascript($this->getDriver()));

        return $this->autocompleteHelper->getSelectedItems(
            $this->getDriver(),
            $this->getElement('collections')->getXpath(),
        );
    }

    public function associateCollections(array $collectionsNames): void
    {
        Assert::true(DriverHelper::isJavascript($this->getDriver()));

        $collectionsElementXpath = $this->getElement('collections')->getXpath();

        foreach ($collectionsNames as $collectionName) {
            $this->autocompleteHelper->selectByName(
                $this->getDriver(),
                $collectionsElementXpath,
                $collectionName,
            );

            $this->waitForFormUpdate();
        }
    }

    public function selectTemplate(string $templateName): void
    {
        $this->getElement('template')->selectOption($templateName);
    }

    public function selectChannel(string $code): void
    {
        $this->getDocument()->checkField($code);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'form' => '[data-live-name-value="sylius_cms:admin:page:form"]',
                'collections' => '[data-test-collections]',
                'template' => '[data-test-template]',
            ],
        );
    }
}
