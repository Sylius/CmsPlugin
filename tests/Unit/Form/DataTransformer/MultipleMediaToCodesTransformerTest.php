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

namespace Tests\Sylius\CmsPlugin\Unit\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\DataTransformer\MultipleMediaToCodesTransformer;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;

final class MultipleMediaToCodesTransformerTest extends TestCase
{
    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    private MultipleMediaToCodesTransformer $multipleMediaToCodesTransformer;

    protected function setUp(): void
    {
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->multipleMediaToCodesTransformer = new MultipleMediaToCodesTransformer($this->mediaRepositoryMock);
    }

    public function testTransformsNullValueToEmptyCollection(): void
    {
        self::assertInstanceOf(ArrayCollection::class, $this->multipleMediaToCodesTransformer->transform(null));
        self::assertSame(0, $this->multipleMediaToCodesTransformer->transform(null)->count());
    }

    public function testTransformsEmptyArrayToEmptyCollection(): void
    {
        self::assertInstanceOf(ArrayCollection::class, $this->multipleMediaToCodesTransformer->transform([]));
        self::assertSame(0, $this->multipleMediaToCodesTransformer->transform([])->count());
    }

    public function testReverseTransformsEmptyCollectionToEmptyArray(): void
    {
        $result = $this->multipleMediaToCodesTransformer->reverseTransform(new ArrayCollection());

        self::assertIsIterable($result);
        self::assertSame([], $result);
    }

    public function testTransformsNonEmptyArrayToCollection(): void
    {
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);
        /** @var MediaInterface&MockObject $media2Mock */
        $media2Mock = $this->createMock(MediaInterface::class);
        $mediaCodes = ['code1', 'code2'];

        $this->mediaRepositoryMock->expects(self::once())->method('findBy')->with(['code' => $mediaCodes])->willReturn([$media1Mock, $media2Mock]);

        $result = $this->multipleMediaToCodesTransformer->transform($mediaCodes);

        self::assertInstanceOf(ArrayCollection::class, $result);
        self::assertCount(2, $result);
        self::assertSame([$media1Mock, $media2Mock], $result->toArray());
    }

    public function testReverseTransformsCollectionToArrayOfMediaCodes(): void
    {
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);
        /** @var MediaInterface&MockObject $media2Mock */
        $media2Mock = $this->createMock(MediaInterface::class);
        $media1Mock->expects(self::once())->method('getCode')->willReturn('code1');
        $media2Mock->expects(self::once())->method('getCode')->willReturn('code2');

        self::assertSame(['code1', 'code2'], $this->multipleMediaToCodesTransformer->reverseTransform(new ArrayCollection([$media1Mock, $media2Mock])));
    }
}
