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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Sylius\CmsPlugin\Resolver\MediaResourceResolverInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MediaController extends ResourceController
{
    use ResourceDataProcessingTrait;
    use MediaPageControllersCommonDependencyInjectionsTrait;

    private MediaResourceResolverInterface $mediaResourceResolver;

    private MediaProviderResolverInterface $mediaProviderResolver;

    public const FILTER = 'sylius_admin_product_original';

    public function renderMediaAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        /** @var MediaInterface|null $media */
        $media = $this->getMediaForRequestCode($configuration, $request);
        if (null === $media) {
            return throw new NotFoundHttpException();
        }

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $media);

        return new Response($this->mediaProviderResolver->resolveProvider($media)->render($media, $request->get('template')));
    }

    public function downloadMediaAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        /** @var MediaInterface|null $media */
        $media = $this->getMediaForRequestCode($configuration, $request);
        if (null === $media) {
            return throw new NotFoundHttpException();
        }

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $media);

        $mediaPath = $this->getMediaPathIfNotNull($media);
        $mediaFile = new File($mediaPath);
        $mediaName = $media->getDownloadName() . '.' . $mediaFile->guessExtension();
        $response = new BinaryFileResponse($mediaPath);

        $response->setContentDisposition($this->resolveDisposition($request), $mediaName);
        $response->headers->set('Content-Type', $media->getMimeType());

        return $response;
    }

    public function setMediaProviderResolver(MediaProviderResolverInterface $mediaProviderResolver): void
    {
        $this->mediaProviderResolver = $mediaProviderResolver;
    }

    public function setMediaResourceResolver(MediaResourceResolverInterface $mediaResourceResolver): void
    {
        $this->mediaResourceResolver = $mediaResourceResolver;
    }

    private function getMediaForRequestCode(RequestConfiguration $configuration, Request $request): ?MediaInterface
    {
        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $code = $request->get('code');

        return $this->mediaResourceResolver->findOrLog($code);
    }

    private function resolveDisposition(Request $request): string
    {
        $disposition = $request->query->get('disposition');
        if (in_array($disposition, [ResponseHeaderBag::DISPOSITION_ATTACHMENT, ResponseHeaderBag::DISPOSITION_INLINE], true)) {
            return $disposition;
        }

        return $request->attributes->get('disposition', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
