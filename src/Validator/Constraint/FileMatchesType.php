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
    public string $messageImage = 'sylius_cms.media.file.type_image';

    public string $messageVideo = 'sylius_cms.media.file.type_video';

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
        $this->messageImage = $this->normalizeMessage($messageImage ?? $this->messageImage, 'sylius_cms.media.file.type_image', 'This file cannot be uploaded as an image');
        $this->messageVideo = $this->normalizeMessage($messageVideo ?? $this->messageVideo, 'sylius_cms.media.file.type_video', 'This file cannot be uploaded as an video');
    }

    private function normalizeMessage(string $message, string $newKey, string $oldValue): string
    {
        return $message === $oldValue ? $newKey : $message;
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
