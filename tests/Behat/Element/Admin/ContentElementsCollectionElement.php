<?php

declare(strict_types=1);

namespace Tests\Sylius\CmsPlugin\Behat\Element\Admin;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Sylius\Behat\Element\Admin\Crud\FormElement;
use Webmozart\Assert\Assert;

class ContentElementsCollectionElement extends FormElement implements ContentElementsCollectionElementInterface
{
    public function __construct(
        Session $session,
        MinkParameters|array $minkParameters = [],
        private readonly string $defaultLocaleCode = 'en_US',
    ) {
        parent::__construct($session, $minkParameters);
    }

    public function hasContentElement(string $type): bool
    {
        try {
            $this->getContentElementOfType($type);

            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    public function getContentElementsCount(): int
    {
        return count($this->getContentElements());
    }

    public function addContentElement(string $type): void
    {
        $addElementDropdown = $this->getElement('content_elements_add');
        $addElementDropdown->click();
        $addElementDropdown->find('css', sprintf('li:contains("%s")', $type))?->click();

        $this->waitForFormUpdate();
    }

    public function removeContentElement(string $type): void
    {
        $element = $this->getContentElementOfType($type);

        $element->find('css', '[data-test-delete-action]')?->click();

        $this->waitForFormUpdate();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'form' => '[data-live-name-value^="sylius_cms:admin"][data-live-name-value$=":form"]',
            'content_elements_container' => '#translation-elements-%locale% [data-test-elements-collection]',
            'content_elements_add' => '#translation-elements-%locale% [data-test-add-element-button]',
        ]);
    }

    protected function getContentElementOfType(string $typeLabel): NodeElement
    {
        $selector = sprintf('option[selected]:contains("%s")', $typeLabel);
        foreach ($this->getContentElements() as $item) {
            $foundElement = $item->find('css', $selector);
            if (!$foundElement instanceof NodeElement) {
                continue;
            }

            return $item;
        }

        throw new \InvalidArgumentException(sprintf('Content element "%s" not found.', $typeLabel));
    }

    /** @return NodeElement[] */
    protected function getContentElements(): array
    {
        $container = $this->getElement('content_elements_container', ['%locale%' => $this->defaultLocaleCode]);
        $elements = $container->findAll('css', '[data-test-entry-row]');
        Assert::notEmpty($elements, 'Content elements not found.');

        return $elements;
    }
}
