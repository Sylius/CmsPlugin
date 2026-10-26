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
use Symfony\Component\Validator\Exception\InvalidArgumentException;

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
        if (is_array($type)) {
            if (!isset($type['type']) && !isset($type['value'])) {
                throw new InvalidArgumentException('The "type" option is required when using array-based constructor.');
            }

            $options = $type;
            $type = $options['type'] ?? $options['value'] ?? null;
            $message = $options['message'] ?? $message;
            $groups = $options['groups'] ?? $groups;
            $payload = $options['payload'] ?? $payload;
        }

        parent::__construct(null, $groups, $payload);

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
