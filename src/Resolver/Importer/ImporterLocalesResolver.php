<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Resolver\Importer;

use Sylius\CmsPlugin\Assigner\LocalesAssignerInterface;
use Sylius\CmsPlugin\Entity\LocaleAwareInterface;

final class ImporterLocalesResolver implements ImporterLocalesResolverInterface
{
    public function __construct(private LocalesAssignerInterface $localesAssigner)
    {
    }

    public function resolve(LocaleAwareInterface $localesAware, ?string $localesRow): void
    {
        if (empty($localesRow)) {
            return;
        }

        $channelsCodes = explode(',', $localesRow);
        $channelsCodes = array_map(static function (string $element): string {
            return trim($element);
        }, $channelsCodes);

        $this->localesAssigner->assign($localesAware, $channelsCodes);
    }
}
