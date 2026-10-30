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
use Sylius\CmsPlugin\Validator\Constraint\CollectionMatchesType;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class CollectionMatchesTypeTest extends TestCase
{
    public function testItIsConfiguredWithNamedArguments(): void
    {
        $constraint = new CollectionMatchesType(type: 'page', message: 'custom', groups: ['cms']);

        self::assertSame('page', $constraint->type);
        self::assertSame('custom', $constraint->message);
        self::assertSame(['cms'], $constraint->groups);
    }

    public function testItKeepsTheDefaultMessage(): void
    {
        $constraint = new CollectionMatchesType(type: 'block');

        self::assertSame('sylius_cms.collection.invalid_type', $constraint->message);
    }

    public function testItStillAcceptsTheDeprecatedOptionsArray(): void
    {
        $deprecations = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$deprecations): bool {
            $deprecations[] = $errstr;

            return true;
        }, \E_USER_DEPRECATED);

        try {
            $constraint = new CollectionMatchesType(['type' => 'media', 'message' => 'legacy', 'groups' => ['cms']]);
        } finally {
            restore_error_handler();
        }

        self::assertSame('media', $constraint->type);
        self::assertSame('legacy', $constraint->message);
        self::assertSame(['cms'], $constraint->groups);
        self::assertCount(1, $deprecations);
        self::assertStringContainsString('named arguments', $deprecations[0]);
    }

    public function testItRequiresTheType(): void
    {
        $this->expectException(MissingOptionsException::class);

        new CollectionMatchesType();
    }
}
