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

namespace Tests\Sylius\CmsPlugin\Unit\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectibleInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Form\Type\CollectionType;
use Sylius\CmsPlugin\Validator\CollectionMatchesTypeValidator;
use Sylius\CmsPlugin\Validator\Constraint\CollectionMatchesType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class CollectionMatchesTypeValidatorTest extends TestCase
{
    private CollectionMatchesTypeValidator $validator;

    private ExecutionContextInterface&MockObject $context;

    protected function setUp(): void
    {
        $this->validator = new CollectionMatchesTypeValidator();
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator->initialize($this->context);
    }

    public function testNoViolationWhenCollectionsMatchType(): void
    {
        $constraint = new CollectionMatchesType();
        $constraint->type = CollectionType::PAGE;

        $collection = $this->createMock(CollectionInterface::class);
        $collection->method('getType')->willReturn(CollectionType::PAGE);

        $collectible = $this->createMock(CollectibleInterface::class);
        $collectible->method('getCollections')->willReturn(new ArrayCollection([$collection]));

        $this->context->expects(self::never())->method('buildViolation');

        $this->validator->validate($collectible, $constraint);
    }

    public function testViolationWhenCollectionTypeDoesNotMatch(): void
    {
        $constraint = new CollectionMatchesType();
        $constraint->type = CollectionType::PAGE;

        $collection = $this->createMock(CollectionInterface::class);
        $collection->method('getType')->willReturn(CollectionType::BLOCK);
        $collection->method('getName')->willReturn('My Block Collection');

        $collectible = $this->createMock(CollectibleInterface::class);
        $collectible->method('getCollections')->willReturn(new ArrayCollection([$collection]));

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('atPath')->willReturnSelf();
        $violationBuilder->method('setParameter')->willReturnSelf();
        $violationBuilder->expects(self::once())->method('addViolation');

        $this->context->expects(self::once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        $this->validator->validate($collectible, $constraint);
    }

    public function testNoViolationWhenCollectionsAreEmpty(): void
    {
        $constraint = new CollectionMatchesType();
        $constraint->type = CollectionType::PAGE;

        $collectible = $this->createMock(CollectibleInterface::class);
        $collectible->method('getCollections')->willReturn(new ArrayCollection());

        $this->context->expects(self::never())->method('buildViolation');

        $this->validator->validate($collectible, $constraint);
    }

    public function testViolationForEachInvalidCollection(): void
    {
        $constraint = new CollectionMatchesType();
        $constraint->type = CollectionType::MEDIA;

        $validCollection = $this->createMock(CollectionInterface::class);
        $validCollection->method('getType')->willReturn(CollectionType::MEDIA);

        $invalidCollection1 = $this->createMock(CollectionInterface::class);
        $invalidCollection1->method('getType')->willReturn(CollectionType::PAGE);
        $invalidCollection1->method('getName')->willReturn('Page Collection');

        $invalidCollection2 = $this->createMock(CollectionInterface::class);
        $invalidCollection2->method('getType')->willReturn(CollectionType::BLOCK);
        $invalidCollection2->method('getName')->willReturn('Block Collection');

        $collectible = $this->createMock(CollectibleInterface::class);
        $collectible->method('getCollections')->willReturn(
            new ArrayCollection([$validCollection, $invalidCollection1, $invalidCollection2]),
        );

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('atPath')->willReturnSelf();
        $violationBuilder->method('setParameter')->willReturnSelf();
        $violationBuilder->expects(self::exactly(2))->method('addViolation');

        $this->context->expects(self::exactly(2))
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        $this->validator->validate($collectible, $constraint);
    }
}
