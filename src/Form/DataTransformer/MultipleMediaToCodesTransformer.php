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

namespace Sylius\CmsPlugin\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Webmozart\Assert\Assert;

/** @implements DataTransformerInterface<array<string>|null, Collection<array-key, MediaInterface>> */
final class MultipleMediaToCodesTransformer implements DataTransformerInterface
{
    public function __construct(private MediaRepositoryInterface $mediaRepository)
    {
    }

    /** @return Collection<array-key, MediaInterface> */
    public function transform(mixed $value): Collection
    {
        Assert::nullOrIsArray($value);

        if (empty($value)) {
            return new ArrayCollection();
        }

        return new ArrayCollection($this->mediaRepository->findBy(['code' => $value]));
    }

    /** @return array<string|null> */
    public function reverseTransform(mixed $value): array
    {
        Assert::isInstanceOf($value, Collection::class);

        $mediaCodes = [];

        /** @var MediaInterface $media */
        foreach ($value as $media) {
            $mediaCodes[] = $media->getCode();
        }

        return $mediaCodes;
    }
}
