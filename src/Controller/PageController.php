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
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Provider\ResourceTemplateProvider;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class PageController extends ResourceController
{
    use ResourceDataProcessingTrait;
    use MediaPageControllersCommonDependencyInjectionsTrait;

    public const FILTER = 'sylius_admin_product_original';

    public function showAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        $slug = $request->attributes->get('slug');

        /** @var PageRepositoryInterface $pageRepository */
        $pageRepository = $this->get('sylius_cms.repository.page');

        /** @var LocaleContextInterface $localeContext */
        $localeContext = $this->get('sylius.context.locale');

        /** @var ChannelContextInterface $channelContext */
        $channelContext = $this->get('sylius.context.channel');

        Assert::notNull($channelContext->getChannel()->getCode());

        $page = $pageRepository->findOneEnabledBySlugAndChannelCode(
            $slug,
            $localeContext->getLocaleCode(),
            $channelContext->getChannel()->getCode(),
        );

        if (null === $page) {
            throw $this->createNotFoundException('Page not found');
        }

        return $this->render($page->getTemplate() ?? ResourceTemplateProvider::DEFAULT_TEMPLATE_PAGE, [
            'page' => $page,
        ]);
    }

    public function previewAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);

        /** @var PageInterface $page */
        $page = $this->getResourceInterface($request);
        $form = $this->getFormForResource($configuration, $page);
        $defaultLocale = $this->getParameter('locale');

        $form->handleRequest($request);

        $page->setFallbackLocale($request->get('_locale', $defaultLocale));
        $page->setCurrentLocale($request->get('_locale', $defaultLocale));

        $this->formErrorsFlashHelper->addFlashErrors($form);

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, $page, Response::HTTP_OK);
        }

        return $this->render($configuration->getTemplate(ResourceActions::CREATE . '.html'), [
            'resource' => $page,
            'preview' => true,
            'template' => $page->getTemplate() ?? ResourceTemplateProvider::DEFAULT_TEMPLATE_PAGE,
            $this->metadata->getName() => $page,
        ]);
    }
}
