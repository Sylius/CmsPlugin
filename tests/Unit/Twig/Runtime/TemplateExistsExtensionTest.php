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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Runtime;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\StaticTemplateAwareInterface;
use Sylius\CmsPlugin\Twig\Runtime\TemplateExistsRuntime;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Template;
use Twig\TemplateWrapper;

final class TemplateExistsExtensionTest extends TestCase
{
    private Environment|MockObject $twig;

    private TemplateExistsRuntime $runtime;

    private TemplateWrapper $templateWrapperStub;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);

        $this->runtime = new TemplateExistsRuntime($this->twig);

        $this->templateWrapperStub = new TemplateWrapper($this->twig, $this->createMock(Template::class));
    }

    #[DataProvider('validTemplates')]
    public function testReturnsTrue(StaticTemplateAwareInterface|string $templateAware): void
    {
        $this->twig
            ->expects(self::once())
            ->method('load')
            ->with($templateAware instanceof StaticTemplateAwareInterface ? $templateAware->getTemplate() : $templateAware)
            ->willReturn($this->templateWrapperStub)
        ;

        self::assertTrue($this->runtime->exists($templateAware));
    }

    #[DataProvider('emptyTemplates')]
    public function testReturnsFalseOnEmptyTemplate(StaticTemplateAwareInterface|string|null $templateAware): void
    {
        $this->twig->expects(self::never())->method('load');

        self::assertFalse($this->runtime->exists($templateAware));
    }

    #[DataProvider('missingTemplates')]
    public function testReturnsFalseOnMissingTemplate(StaticTemplateAwareInterface|string $templateAware): void
    {
        $this->twig
            ->expects(self::once())
            ->method('load')
            ->with($templateAware instanceof StaticTemplateAwareInterface ? $templateAware->getTemplate() : $templateAware)
            ->willThrowException(new LoaderError(''))
        ;

        self::assertFalse($this->runtime->exists($templateAware));
    }

    /** @return iterable<string, array{StaticTemplateAwareInterface|string}> */
    public static function validTemplates(): iterable
    {
        yield 'string template' => ['@SyliusCmsPlugin/shop/block/show.html.twig'];
        yield 'template aware' => [new StaticTemplateStub('@SyliusCmsPlugin/shop/block/show.html.twig')];
    }

    /** @return iterable<string, array{StaticTemplateAwareInterface|string|null}> */
    public static function emptyTemplates(): iterable
    {
        yield 'null' => [null];
        yield 'empty string' => [''];
        yield 'template aware with empty template' => [new StaticTemplateStub('')];
        yield 'template aware with null template' => [new StaticTemplateStub(null)];
    }

    /** @return iterable<string, array{StaticTemplateAwareInterface|string}> */
    public static function missingTemplates(): iterable
    {
        yield 'missing string template' => ['@SyliusCmsPlugin/shop/block/missing.html.twig'];
        yield 'missing template aware' => [new StaticTemplateStub('@SyliusCmsPlugin/shop/block/missing.html.twig')];
    }
}

class StaticTemplateStub implements StaticTemplateAwareInterface
{
    public function __construct(private readonly ?string $template = null)
    {
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        // do nothing
    }
}
