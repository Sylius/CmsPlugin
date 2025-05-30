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
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Page\CreatePageInterface;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Page\IndexPageInterface;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Page\UpdatePageInterface;
use Tests\Sylius\CmsPlugin\Behat\Service\RandomStringGeneratorInterface;
use Webmozart\Assert\Assert;

final class PageContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private CurrentPageResolverInterface $currentPageResolver,
        private NotificationCheckerInterface $notificationChecker,
        private IndexPageInterface $indexPage,
        private CreatePageInterface $createPage,
        private UpdatePageInterface $updatePage,
        private RandomStringGeneratorInterface $randomStringGenerator,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    /**
     * @When I go to the cms pages page
     */
    public function iGoToTheCmsPagesPage(): void
    {
        $this->indexPage->open();
    }

    /**
     * @When I go to the create page page
     */
    public function iGoToTheCreatePagePage(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I delete this page
     */
    public function iDeleteThisPage(): void
    {
        $page = $this->sharedStorage->get('page');

        $this->indexPage->deletePage($page->getCode());
    }

    /**
     * @When I want to edit this page
     */
    public function iWantToEditThisPage(): void
    {
        $page = $this->sharedStorage->get('page');

        $this->updatePage->open(['id' => $page->getId()]);
    }

    /**
     * @When I go to the update :code page page
     */
    public function iGoToTheUpdatePagePage(string $code): void
    {
        $page = $this->pageRepository->findOneBy(['code' => $code]);

        Assert::notNull($page);

        $this->sharedStorage->set('page_code', $code);

        $this->updatePage->open(['id' => $page->getId()]);
    }

    /**
     * @When I fill the code with :code
     */
    public function iFillTheCodeWith(string $code): void
    {
        $this->resolveCurrentPage()->fillCode($code);

        $this->sharedStorage->set('page_code', $code);
    }

    /**
     * @When I fill the name with :name
     */
    public function iFillTheNameWith(string $name): void
    {
        $this->resolveCurrentPage()->fillName($name);
    }

    /**
     * @When I select :code channel
     */
    public function iSelectChannel(string $code): void
    {
        $this->resolveCurrentPage()->selectChannel($code);
    }

    /**
     * @When I fill the slug with :slug
     */
    public function iFillTheSlugWith(string $slug): void
    {
        $this->resolveCurrentPage()->fillSlug($slug);
    }

    /**
     * @When I fill the meta keywords with :metaKeywords
     */
    public function iFillTheMetaKeywordsWith(string $metaKeywords): void
    {
        $this->resolveCurrentPage()->fillMetaKeywords($metaKeywords);
    }

    /**
     * @When I fill the meta description with :metaDescription
     */
    public function iFillTheMetaDescriptionWith(string $metaDescription): void
    {
        $this->resolveCurrentPage()->fillMetaDescription($metaDescription);
    }

    /**
     * @When I fill the content with :content
     */
    public function iFillTheContentWith(string $content): void
    {
        $this->resolveCurrentPage()->fillContent($content);
    }

    /**
     * @When /^I fill "([^"]*)" fields with (\d+) (?:character|characters)$/
     */
    public function iFillFieldsWithCharacters(string $fields, int $length): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            $this->resolveCurrentPage()->fillField(trim($field), $this->randomStringGenerator->generate($length));
        }
    }

    /**
     * @When I fill :fields fields
     */
    public function iFillFields(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            $this->resolveCurrentPage()->fillField(trim($field), $this->randomStringGenerator->generate());
        }
    }

    /**
     * @When I add :firstCollection and :secondCollection collections to it
     */
    public function iAddAndCollectionsToIt(string ...$collectionsNames): void
    {
        $this->resolveCurrentPage()->associateCollections($collectionsNames);
    }

    /**
     * @Then I select :templateName template
     */
    public function iSelectTemplate(string $templateName): void
    {
        $this->resolveCurrentPage()->selectTemplate($templateName);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt(): void
    {
        $this->resolveCurrentPage()->create();
    }

    /**
     * @When I update it
     */
    public function iUpdateIt(): void
    {
        $this->resolveCurrentPage()->saveChanges();
    }

    /**
     * @Then I should be notified that the page has been created
     */
    public function iShouldBeNotifiedThatNewPageWasCreated(): void
    {
        $this->notificationChecker->checkNotification(
            'Page has been successfully created.',
            NotificationType::success(),
        );
    }

    /**
     * @Then I should be notified that the page was updated
     */
    public function iShouldBeNotifiedThatThePageWasUpdated(): void
    {
        $this->notificationChecker->checkNotification(
            'Page has been successfully updated.',
            NotificationType::success(),
        );
    }

    /**
     * @Then I should be notified that the page has been deleted
     */
    public function iShouldBeNotifiedThatThePageHasBeenDeleted(): void
    {
        $this->notificationChecker->checkNotification(
            'Page has been successfully deleted.',
            NotificationType::success(),
        );
    }

    /**
     * @Then I should be notified that there is already an existing page with provided code
     */
    public function iShouldBeNotifiedThatThereIsAlreadyAnExistingPageWithCode(): void
    {
        Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(
            'There is an existing page with this code.',
            false,
        ));
    }

    /**
     * @Then I should be notified that :fields fields cannot be blank
     */
    public function iShouldBeNotifiedThatFieldsCannotBeBlank(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(sprintf(
                '%s cannot be blank.',
                trim($field),
            )));
        }
    }

    /**
     * @Then I should be notified that :fields fields are too short
     */
    public function iShouldBeNotifiedThatFieldsAreTooShort(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(sprintf(
                '%s must be at least %d characters long.',
                trim($field),
                2,
            )));
        }
    }

    /**
     * @Then I should be notified that :fields fields are too long
     */
    public function iShouldBeNotifiedThatFieldsAreTooLong(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->resolveCurrentPage()->containsErrorWithMessage(sprintf(
                '%s can not be longer than',
                trim($field),
            ), false));
        }
    }

    /**
     * @Then this page should have collections :firstCollection and :secondCollection
     */
    public function thisPageShouldHaveCollections(string ...$collectionsNames): void
    {
        $pageCollections = $this->resolveCurrentPage()->getCollections();
        foreach ($collectionsNames as $name) {
            Assert::inArray(
                $name,
                $pageCollections,
                sprintf('Collection "%s" should be present in the page.', $name),
            );
        }
    }

    /**
     * @Then only :number pages should exist in the store
     */
    public function onlyPagesShouldAppearInTheStore(int $number): void
    {
        Assert::eq($number, $this->resolveCurrentPage()->countItems());
    }

    /**
     * @Then the code field should be disabled
     */
    public function theCodeFieldShouldBeDisabled()
    {
        Assert::true($this->resolveCurrentPage()->isCodeDisabled());
    }

    /**
     * @Then I should see empty list of pages
     */
    public function iShouldSeeEmptyListOfBlocks(): void
    {
        $this->resolveCurrentPage()->isEmpty();
    }

    /**
     * @When I choose :code media as image
     */
    public function iChooseMediaAsImage(string $code): void
    {
        $this->resolveCurrentPage()->chooseImage($code);
    }

    /**
     * @return IndexPageInterface|CreatePageInterface|UpdatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
            $this->createPage,
            $this->updatePage,
        ]);
    }
}
