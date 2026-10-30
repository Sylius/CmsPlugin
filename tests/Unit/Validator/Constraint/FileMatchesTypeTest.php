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

namespace Tests\Sylius\CmsPlugin\Unit\Validator\Constraint;

use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Validator\Constraint\FileMatchesType;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class FileMatchesTypeTest extends TestCase
{
    public function testItRequiresTheField(): void
    {
        $this->expectException(MissingOptionsException::class);

        new FileMatchesType();
    }

    public function testItIsConfiguredWithNamedArguments(): void
    {
        $constraint = new FileMatchesType(field: 'cover', messageImage: 'image', messageVideo: 'video', groups: ['cms']);

        self::assertSame('cover', $constraint->field);
        self::assertSame('image', $constraint->messageImage);
        self::assertSame('video', $constraint->messageVideo);
        self::assertSame(['cms'], $constraint->groups);
    }

    public function testItUsesTranslationKeysAsDefaultMessages(): void
    {
        $constraint = new FileMatchesType(field: 'type');

        self::assertSame('sylius_cms.media.file.type_image', $constraint->messageImage);
        self::assertSame('sylius_cms.media.file.type_video', $constraint->messageVideo);
    }

    public function testItStillAcceptsTheDeprecatedOptionsArray(): void
    {
        $deprecations = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$deprecations): bool {
            $deprecations[] = $errstr;

            return true;
        }, \E_USER_DEPRECATED);

        try {
            $constraint = new FileMatchesType(['field' => 'type', 'messageImage' => 'legacy', 'groups' => ['cms']]);
        } finally {
            restore_error_handler();
        }

        self::assertSame('type', $constraint->field);
        self::assertSame('legacy', $constraint->messageImage);
        self::assertSame('sylius_cms.media.file.type_video', $constraint->messageVideo);
        self::assertSame(['cms'], $constraint->groups);
        self::assertCount(1, $deprecations);
        self::assertStringContainsString('named arguments', $deprecations[0]);
    }
}
