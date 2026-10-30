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
use Symfony\Component\Validator\Exception\MissingOptionsException;

#[\Attribute]
final class CollectionMatchesType extends Constraint
{
    public string $type;

    /**
     * @param array<string, mixed>|null $options
     */
    #[HasNamedArguments]
    public function __construct(
        ?array $options = null,
        ?string $type = null,
        public string $message = 'sylius_cms.collection.invalid_type',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        if (null === $type && !isset($options['type'])) {
            throw new MissingOptionsException(sprintf('The option "type" must be set for constraint "%s".', self::class), ['type']);
        }

        $this->type = $type ?? $options['type'];

        if (\is_array($options)) {
            trigger_deprecation(
                'sylius/cms-plugin',
                '1.2',
                'Passing an array of options to configure the "%s" constraint is deprecated and will be removed in CMS Plugin 2.0, use named arguments instead.',
                static::class,
            );

            $this->message = $options['message'] ?? $this->message;
            $groups ??= $options['groups'] ?? null;
            $payload ??= $options['payload'] ?? null;
        }

        parent::__construct(groups: $groups, payload: $payload);
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
