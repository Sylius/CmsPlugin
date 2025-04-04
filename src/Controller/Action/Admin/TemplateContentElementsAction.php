<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Controller\Action\Admin;

use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TemplateContentElementsAction
{
    public function __construct(private RepositoryInterface $templateRepository)
    {
    }

    public function getContentElementsAction(int $id): JsonResponse
    {
        /** @var TemplateInterface|null $template */
        $template = $this->templateRepository->find($id);
        if (null === $template) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Template not found',
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'content' => $template->getContentElements(),
        ]);
    }
}
