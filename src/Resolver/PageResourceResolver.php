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

namespace Sylius\CmsPlugin\Resolver;

use Psr\Log\LoggerInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;

final class PageResourceResolver implements PageResourceResolverInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function findOrLog(string $code): ?PageInterface
    {
        $page = $this->pageRepository->findOneEnabledByCode($code);

        if (false === $page instanceof PageInterface) {
            $this->logger->warning(sprintf(
                'Page with "%s" code was not found in the database.',
                $code,
            ));

            return null;
        }

        return $page;
    }
}
