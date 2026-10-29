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

use Sylius\CmsPlugin\Validator\FileMatchesTypeValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class FileMatchesType extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        ?array $options = null,
        public string $field = '',
        public string $messageImage = 'sylius_cms.media.file.type_image',
        public string $messageVideo = 'sylius_cms.media.file.type_video',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        if (\is_array($options)) {
            trigger_deprecation(
                'sylius/cms-plugin',
                '1.2',
                'Passing an array of options to configure the "%s" constraint is deprecated and will be removed in CMS Plugin 2.0, use named arguments instead.',
                static::class,
            );

            $this->field = $options['field'] ?? $this->field;
            $this->messageImage = $options['messageImage'] ?? $this->messageImage;
            $this->messageVideo = $options['messageVideo'] ?? $this->messageVideo;
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
        return FileMatchesTypeValidator::class;
    }
}
