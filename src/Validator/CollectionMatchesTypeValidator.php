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

namespace Sylius\CmsPlugin\Validator;

use Sylius\CmsPlugin\Entity\CollectibleInterface;
use Sylius\CmsPlugin\Validator\Constraint\CollectionMatchesType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class CollectionMatchesTypeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CollectionMatchesType) {
            throw new UnexpectedTypeException($constraint, CollectionMatchesType::class);
        }

        if (!$value instanceof CollectibleInterface) {
            throw new UnexpectedValueException($value, CollectibleInterface::class);
        }

        foreach ($value->getCollections() as $collection) {
            if ($collection->getType() !== $constraint->type) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('collections')
                    ->setParameter('{{ collection }}', (string) $collection->getName())
                    ->setParameter('{{ type }}', $constraint->type)
                    ->addViolation()
                ;
            }
        }
    }
}
