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
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
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

    protected string $previewTemplate;

    #[LiveProp(writable: true)]
    public string $localeCode = '';

    #[LiveAction]
    public function preview(): void
    {
        if (null === $this->resource) {
            return;
        }

        $this->submitForm();

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

    protected function initializePreview(Environment $twig, string $previewTemplate): void
    {
        $this->twig = $twig;
        $this->previewTemplate = $previewTemplate;
    }

    private function dispatchPreviewEvent(string $eventName): void
    {
        $this->dispatchBrowserEvent($eventName, [
            'content' => $this->twig->render($this->previewTemplate, [
                'resource' => $this->resource,
            ]),
        ]);
    }
}
