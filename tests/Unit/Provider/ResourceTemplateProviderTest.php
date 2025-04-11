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

namespace Tests\Sylius\CmsPlugin\Unit\Provider;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Provider\ResourceTemplateProvider;
use Sylius\CmsPlugin\Provider\ResourceTemplateProviderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ResourceTemplateProviderTest extends TestCase
{
    /** @var ParameterBagInterface&MockObject */
    private MockObject $parameterBagMock;

    private ResourceTemplateProvider $resourceTemplateProvider;

    protected function setUp(): void
    {
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->parameterBagMock->method('has')->willReturnOnConsecutiveCalls(true, true);
        $this->parameterBagMock->method('get')->willReturnOnConsecutiveCalls(
            ['@CustomTemplate/Page.html.twig' => '@CustomTemplate/Page.html.twig'],
            ['@CustomTemplate/Block.html.twig' => '@CustomTemplate/Block.html.twig'],
        );
        $this->resourceTemplateProvider = new ResourceTemplateProvider($this->parameterBagMock);
    }

    public function testImplementsResourceTemplateProviderInterface(): void
    {
        self::assertInstanceOf(ResourceTemplateProviderInterface::class, $this->resourceTemplateProvider);
    }

    public function testReturnsDefaultAndCustomPageTemplates(): void
    {
        self::assertSame([
            'sylius.ui.default' => '@SyliusCmsPlugin/shop/page/show.html.twig',
            '@CustomTemplate/Page.html.twig' => '@CustomTemplate/Page.html.twig',
        ], $this->resourceTemplateProvider->getPageTemplates());
    }

    public function testReturnsDefaultAndCustomBlockTemplates(): void
    {
        self::assertSame([
            'sylius.ui.default' => '@SyliusCmsPlugin/shop/block/show.html.twig',
            '@CustomTemplate/Block.html.twig' => '@CustomTemplate/Block.html.twig',
        ], $this->resourceTemplateProvider->getBlockTemplates());
    }
}
