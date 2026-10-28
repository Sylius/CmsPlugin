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
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

final class CollectionMatchesType extends Constraint
{
    public string $message = 'sylius_cms.collection.invalid_type';

    public string $type;

    /**
     * @param string|array<string, mixed>|null $type
     */
    #[HasNamedArguments]
    public function __construct(
        array|string|null $type = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        if (\is_array($type)) {
            trigger_deprecation(
                'sylius/cms-plugin',
                '1.2',
                'Passing an array of options to configure the "%s" constraint is deprecated and will be removed in CMS Plugin 2.0, use named arguments instead.',
                static::class,
            );

            $options = $type;
            $type = $options['type'] ?? $options['value'] ?? null;
            $message ??= $options['message'] ?? null;
            $groups ??= $options['groups'] ?? null;
            $payload ??= $options['payload'] ?? null;
        }

        parent::__construct(groups: $groups, payload: $payload);

        $this->type = $type;
        $this->message = $message ?? $this->message;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return CollectionMatchesTypeValidator::class;
    }
}
