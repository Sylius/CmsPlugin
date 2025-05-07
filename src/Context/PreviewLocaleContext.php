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

namespace Sylius\CmsPlugin\Context;

use Sylius\Bundle\AdminBundle\SectionResolver\AdminSection;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class PreviewLocaleContext implements LocaleContextInterface
{
    public function __construct(
        private SectionProviderInterface $sectionProvider,
        private RequestStack $requestStack,
        private LocaleProviderInterface $localeProvider,
    ) {
    }

    public function getLocaleCode(): string
    {
        if (!$this->sectionProvider->getSection() instanceof AdminSection) {
            throw new LocaleNotFoundException();
        }

        $request = $this->requestStack->getMainRequest();
        if (
            null === $request ||
            !$this->hasLiveActionWithComponent($request) ||
            !$request->request->has('data')
        ) {
            throw new LocaleNotFoundException();
        }

        if ($this->requestHasLiveAction($request, 'preview')) {
            return $this->localeProvider->getDefaultLocaleCode();
        }

        if (!$this->requestHasLiveAction($request, 'reload')) {
            throw new LocaleNotFoundException();
        }

        $matches = [];
        preg_match_all('/"updated":{.*"localeCode":"(\w+)"/xUm', (string) $request->request->get('data'), $matches);

        return $matches[1][0] ?? throw new LocaleNotFoundException();
    }

    private function hasLiveActionWithComponent(Request $request): bool
    {
        return $request->attributes->has('_live_component') && $request->attributes->has('_live_action');
    }

    private function requestHasLiveAction(Request $request, string $action): bool
    {
        return
            $request->attributes->get('_live_action') === $action &&
            str_starts_with($request->attributes->get('_live_component'), 'sylius_cms:admin')
        ;
    }
}
