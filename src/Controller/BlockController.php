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

namespace Sylius\CmsPlugin\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Provider\ResourceTemplateProvider;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Resolver\BlockResourceResolverInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BlockController extends ResourceController
{
    public function renderBlockAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        $code = $request->get('code');
        /** @var BlockResourceResolverInterface $blockResourceResolver */
        $blockResourceResolver = $this->get('sylius_cms.resolver.block_resource');
        $block = $blockResourceResolver->findOrLog($code);

        if (null === $block) {
            return new Response();
        }

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $block);

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, $block, Response::HTTP_OK);
        }

        return $this->render($block->getTemplate() ?? ResourceTemplateProvider::DEFAULT_TEMPLATE_BLOCK, [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $block,
            $this->metadata->getName() => $block,
        ]);
    }

    public function previewAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        /** @var BlockInterface $block */
        $block = $this->newResourceFactory->create($configuration, $this->factory);
        $form = $this->resourceFormFactory->create($configuration, $block);

        $form->handleRequest($request);

        /** @var BlockInterface $block */
        $block = $form->getData();

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, $block, Response::HTTP_OK);
        }

        /** @var ContentElementRendererStrategyInterface $contentElementRendererStrategy */
        $contentElementRendererStrategy = $this->get('sylius_cms.content_element_renderer_strategy');

        return $this->render($configuration->getTemplate(ResourceActions::CREATE . '.html'), [
            'resource' => $block,
            'template' => $block->getTemplate(),
            'content' => $contentElementRendererStrategy->render($block),
            $this->metadata->getName() => $block,
        ]);
    }
}
