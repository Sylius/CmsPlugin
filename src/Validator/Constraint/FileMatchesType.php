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

final class FileMatchesType extends Constraint
{
    public string $messageImage = 'This file cannot be uploaded as an image';

    public string $messageVideo = 'This file cannot be uploaded as an video';

    public string $field;

    #[HasNamedArguments]
    public function __construct(
        string $field,
        ?string $messageImage = null,
        ?string $messageVideo = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);

        $this->field = $field;
        $this->messageImage = $messageImage ?? $this->messageImage;
        $this->messageVideo = $messageVideo ?? $this->messageVideo;
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
