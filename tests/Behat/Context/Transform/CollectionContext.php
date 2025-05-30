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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;
use Webmozart\Assert\Assert;

final class CollectionContext implements Context
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
        private string $locale = 'en_US',
    ) {
    }

    /**
     * @Transform /^collection(?:|s) "([^"]+)"$/
     * @Transform /^"([^"]+)" collection(?:|s)$/
     * @Transform /^(?:a|an) "([^"]+)"$/
     * @Transform :collection
     */
    public function getCollectionByCode(string $collectionCode): CollectionInterface
    {
        $collection = $this->collectionRepository->findOneByCode($collectionCode, $this->locale);

        Assert::notNull(
            $collection,
            sprintf('No collections has been found with code "%s".', $collectionCode),
        );

        return $collection;
    }
}
