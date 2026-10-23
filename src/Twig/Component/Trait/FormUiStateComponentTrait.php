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

namespace Sylius\CmsPlugin\Twig\Component\Trait;

use Sylius\CmsPlugin\Form\UiState\FormUiStateConfiguration;
use Sylius\CmsPlugin\Form\UiState\FormUiStateResolverInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

/**
 * @experimental
 *
 * @mixin ComponentWithFormTrait
 */
trait FormUiStateComponentTrait
{
    #[LiveProp(writable: true)]
    public string $activeLocale = '';

    /** @var array<string, string> */
    #[LiveProp(writable: true)]
    public array $activeElements = [];

    protected FormUiStateResolverInterface $formUiStateResolver;

    protected FormUiStateConfiguration $formUiStateConfiguration;

    /** @return array<string, mixed> */
    public function getUiState(): array
    {
        return $this->formUiStateResolver->resolve(
            $this->getFormView(),
            $this->formUiStateConfiguration,
            $this->activeLocale,
            $this->activeElements,
        );
    }

    protected function initializeUiState(
        FormUiStateResolverInterface $formUiStateResolver,
        FormUiStateConfiguration $formUiStateConfiguration,
    ): void {
        $this->formUiStateResolver = $formUiStateResolver;
        $this->formUiStateConfiguration = $formUiStateConfiguration;
    }

    protected function onCollectionItemActivated(string $propertyPath, int|string|null $key): void
    {
        $locale = $this->formUiStateConfiguration->matchElementsLocale($propertyPath);
        if (null === $locale) {
            return;
        }

        if (null === $key) {
            unset($this->activeElements[$locale]);

            return;
        }

        $this->activeElements[$locale] = (string) $key;
    }
}
