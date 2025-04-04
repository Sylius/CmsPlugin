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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;
use Webmozart\Assert\Assert;

final class PageContext implements Context
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private string $locale = 'en_US',
    ) {
    }

    /**
     * @Transform /^page(?:|s) "([^"]+)"$/
     * @Transform /^"([^"]+)" page(?:|s)$/
     * @Transform /^(?:a|an) "([^"]+)"$/
     * @Transform :page
     */
    public function getPageByCode(string $pageCode): PageInterface
    {
        $page = $this->pageRepository->findOneEnabledByCode($pageCode, $this->locale);

        Assert::notNull(
            $page,
            sprintf('No pages has been found with code "%s".', $pageCode),
        );

        return $page;
    }
}
