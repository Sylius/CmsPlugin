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

namespace Sylius\CmsPlugin\Assigner;

use Sylius\CmsPlugin\Entity\LocaleAwareInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class LocalesAssigner implements LocalesAssignerInterface
{
    /** @param RepositoryInterface<LocaleInterface> $localeRepository */
    public function __construct(private RepositoryInterface $localeRepository)
    {
    }

    public function assign(LocaleAwareInterface $localesAware, array $localesCodes): void
    {
        $locales = $this->localeRepository->findBy(['code' => $localesCodes]);

        foreach ($locales as $locale) {
            $localesAware->addLocale($locale);
        }
    }
}
