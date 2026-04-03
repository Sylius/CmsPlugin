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

namespace Sylius\CmsPlugin\Validator\Constraint;

use Sylius\CmsPlugin\Validator\CollectionMatchesTypeValidator;
use Symfony\Component\Validator\Constraint;

final class CollectionMatchesType extends Constraint
{
    public string $message = 'sylius_cms.collection.invalid_type';

    public string $type;

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return CollectionMatchesTypeValidator::class;
    }
}
