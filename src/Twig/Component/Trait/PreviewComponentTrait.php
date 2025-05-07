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

use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Twig\Environment;

/**
 * @mixin ResourceFormComponentTrait
 * @mixin ComponentToolsTrait
 */
trait PreviewComponentTrait
{
    public const RENDER_PREVIEW_EVENT = 'sylius_cms:admin:preview:render';

    public const RELOAD_PREVIEW_EVENT = 'sylius_cms:admin:preview:reload';

    protected Environment $twig;

    protected LocaleProviderInterface $localeProvider;

    protected string $previewTemplate;

    protected string $defaultLocaleCode = '';

    #[LiveProp(writable: true)]
    public string $localeCode = '';

    #[LiveAction]
    public function preview(): void
    {
        if (null === $this->resource) {
            return;
        }

        $this->resetLocaleCodeChoice();

        $this->dispatchPreviewEvent(self::RENDER_PREVIEW_EVENT);
    }

    #[LiveAction]
    public function reload(): void
    {
        if (null === $this->resource) {
            return;
        }

        $this->dispatchPreviewEvent(self::RELOAD_PREVIEW_EVENT);
    }

    #[PreMount]
    public function resetLocaleCodeChoice(): void
    {
        if ('' === $this->defaultLocaleCode) {
            return;
        }

        $this->localeCode = $this->defaultLocaleCode;
        $this->formValues['localeCode'] = $this->defaultLocaleCode;
    }

    protected function initializePreview(
        Environment $twig,
        LocaleProviderInterface $localeProvider,
        string $previewTemplate,
    ): void {
        $this->twig = $twig;
        $this->localeProvider = $localeProvider;
        $this->previewTemplate = $previewTemplate;

        $this->defaultLocaleCode = $this->localeProvider->getDefaultLocaleCode();
    }

    protected function dispatchPreviewEvent(string $eventName): void
    {
        $this->submitForm();

        $this->beforePreviewDispatch();

        $this->dispatchBrowserEvent($eventName, [
            'content' => $this->twig->render($this->previewTemplate, $this->getRenderParameters()),
        ]);
    }

    /** @return array{resource: ResourceInterface|null} */
    protected function getRenderParameters(): array
    {
        return [
            'resource' => $this->resource,
        ];
    }

    protected function beforePreviewDispatch(): void
    {
    }
}
