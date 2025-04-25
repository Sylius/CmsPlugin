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

namespace Tests\Sylius\CmsPlugin\Unit\Context;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\AdminBundle\SectionResolver\AdminSection;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\CmsPlugin\Context\PreviewLocaleContext;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PreviewLocaleContextTest extends TestCase
{
    private MockObject|RequestStack $requestStack;

    private MockObject|SectionProviderInterface $sectionProvider;

    private PreviewLocaleContext $previewLocaleContext;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->sectionProvider = $this->createMock(SectionProviderInterface::class);

        $this->previewLocaleContext = new PreviewLocaleContext(
            $this->sectionProvider,
            $this->requestStack,
        );
    }

    public function testThrowsExceptionWhenSectionIsNotAdmin(): void
    {
        $this->sectionProvider
            ->expects($this->once())
            ->method('getSection')
            ->willReturn(null)
        ;

        $this->expectException(LocaleNotFoundException::class);
        $this->previewLocaleContext->getLocaleCode();
    }

    public function testThrowsExceptionWhenThereIsNoMainRequest(): void
    {
        $this->sectionProvider
            ->expects($this->once())
            ->method('getSection')
            ->willReturn($this->createMock(AdminSection::class))
        ;

        $this->requestStack
            ->expects($this->once())
            ->method('getMainRequest')
            ->willReturn(null)
        ;

        $this->expectException(LocaleNotFoundException::class);
        $this->previewLocaleContext->getLocaleCode();
    }

    #[DataProvider('getInvalidCriteria')]
    public function testThrowsExceptionWhenMainRequestDoesNotFitCriteria(Request $request): void
    {
        $this->sectionProvider
            ->expects($this->once())
            ->method('getSection')
            ->willReturn($this->createMock(AdminSection::class))
        ;

        $this->requestStack
            ->expects($this->once())
            ->method('getMainRequest')
            ->willReturn($request)
        ;

        $this->expectException(LocaleNotFoundException::class);
        $this->previewLocaleContext->getLocaleCode();
    }

    #[DataProvider('getInvalidData')]
    public function testThrowsExceptionWhenDataDoesNotContainUpdatedLocaleCode(Request $request): void
    {
        $this->sectionProvider
            ->expects($this->once())
            ->method('getSection')
            ->willReturn($this->createMock(AdminSection::class))
        ;

        $this->requestStack
            ->expects($this->once())
            ->method('getMainRequest')
            ->willReturn($request)
        ;

        $this->expectException(LocaleNotFoundException::class);
        $this->previewLocaleContext->getLocaleCode();
    }

    #[DataProvider('getValidData')]
    public function testReturnsUpdatedLocale(Request $request, string $locale): void
    {
        $this->sectionProvider
            ->expects($this->once())
            ->method('getSection')
            ->willReturn($this->createMock(AdminSection::class))
        ;

        $this->requestStack
            ->expects($this->once())
            ->method('getMainRequest')
            ->willReturn($request)
        ;

        $this->assertSame($locale, $this->previewLocaleContext->getLocaleCode());
    }

    /** @return iterable<string, Request[]> */
    public static function getInvalidCriteria(): iterable
    {
        yield 'no live action' => [self::createRequest(null, 'sylius_cms:admin', 'data')];
        yield 'no live component' => [self::createRequest('reload', null, 'data')];
        yield 'no data' => [self::createRequest('reload', 'sylius_cms:admin', null)];
        yield 'invalid live action' => [self::createRequest('invalid', 'sylius_cms:admin', 'data')];
        yield 'invalid live component' => [self::createRequest('reload', 'invalid', 'data')];
    }

    /** @return iterable<string, Request[]> */
    public static function getInvalidData(): iterable
    {
        yield 'no updated' => [self::createRequest('reload', 'sylius_cms:admin', '{"invalid":{}}')];
        yield 'no locale code' => [self::createRequest('reload', 'sylius_cms:admin', '{"updated":{}}')];
        yield 'empty locale code' => [self::createRequest('reload', 'sylius_cms:admin', '{"updated":{"localeCode":""}}')];
    }

    /** @return iterable<string, array{request: Request, locale: string}> */
    public static function getValidData(): iterable
    {
        yield 'only updated data' => [
            'request' => self::createRequest('reload', 'sylius_cms:admin', '{"updated":{"localeCode":"en_US"}}'),
            'locale' => 'en_US',
        ];
        yield 'other updated data after' => [
            'request' => self::createRequest('reload', 'sylius_cms:admin', '{"updated":{"localeCode":"fr_FR","other":"data"}}'),
            'locale' => 'fr_FR',
        ];
        yield 'other updated data before' => [
            'request' => self::createRequest('reload', 'sylius_cms:admin', '{"updated":{"other":"data","localeCode":"de_DE"}}'),
            'locale' => 'de_DE',
        ];
        yield 'more updated data around' => [
            'request' => self::createRequest('reload', 'sylius_cms:admin', '{"updated":{"data":"other"},"updated":"updated","updated":{"localeCode":"ie_GA"},"updated":{"more":"data"}}'),
            'locale' => 'ie_GA',
        ];
    }

    protected static function createRequest(?string $liveAction, ?string $liveComponent, ?string $data): Request
    {
        $request = new Request();

        null === $liveAction ?: $request->attributes->set('_live_action', $liveAction);
        null === $liveComponent ?: $request->attributes->set('_live_component', $liveComponent);
        null === $data ?: $request->request->set('data', $data);

        return $request;
    }
}
