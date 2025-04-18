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
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Sylius\Behat\Service\DriverHelper;
use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ChecksCodeImmutabilityTrait;
use Tests\Sylius\CmsPlugin\Behat\Service\FormHelper;
use Webmozart\Assert\Assert;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutabilityTrait;

    /** @param MinkParameters|array<array-key, mixed> $minkParameters */
    public function __construct(
        Session $session,
        array|MinkParameters $minkParameters,
        RouterInterface $router,
        string $routeName,
        protected readonly AutocompleteHelperInterface $autocompleteHelper,
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

    public function getCollections(): array
    {
        Assert::true(DriverHelper::isJavascript($this->getDriver()));

        return $this->autocompleteHelper->getSelectedItems(
            $this->getDriver(),
            $this->getElement('collections')->getXpath(),
        );
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'form' => '[data-live-name-value="sylius_cms:admin:page:form"]',
            'collections' => '[data-test-collections]',
        ]);
    }
}
