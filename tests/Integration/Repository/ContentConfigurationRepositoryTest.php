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

namespace Tests\Sylius\CmsPlugin\Integration\Repository;

use ApiTestCase\JsonApiTestCase;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Repository\ContentConfigurationRepositoryInterface;

final class ContentConfigurationRepositoryTest extends JsonApiTestCase
{
    public function test_it_finds_content_elements_by_type_with_owners(): void
    {
        $this->loadFixturesFromFile('ContentConfigurationRepositoryTest/test_it_finds_content_elements_by_type_with_owners.yml');
        $this->getEntityManager()->clear();

        $elements = $this->getRepository()->findByTypeWithOwners('pages_collection');

        self::assertCount(2, $elements);

        $owners = array_map(
            static fn (ContentConfigurationInterface $element): ?string => $element->getPage()?->getCode() ?? $element->getBlock()?->getCode(),
            $elements,
        );
        sort($owners);
        self::assertSame(['block1-code', 'page1-code'], $owners);
    }

    private function getRepository(): ContentConfigurationRepositoryInterface
    {
        /** @var ContentConfigurationRepositoryInterface $repository */
        $repository = $this->getEntityManager()->getRepository(ContentConfigurationInterface::class);

        return $repository;
    }
}
