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
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Webmozart\Assert\Assert;

final class MediaContext implements Context
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepositoryInterface,
        private SharedStorageInterface $sharedStorage,
    ) {
    }

    /**
     * @Transform /^media(?:|s) "([^"]+)"$/
     * @Transform /^"([^"]+)" media(?:|s)$/
     * @Transform /^(?:a|an) "([^"]+)"$/
     * @Transform :media
     */
    public function getMediaByCode(string $mediaCode): MediaInterface
    {
        $media = $this->mediaRepositoryInterface->findOneEnabledByCode(
            $mediaCode,
            $this->sharedStorage->get('channel')->getCode(),
        );

        Assert::notNull(
            $media,
            sprintf('No media has been found with code "%s".', $mediaCode),
        );

        return $media;
    }
}
