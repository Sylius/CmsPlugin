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

namespace Sylius\CmsPlugin\Controller;

use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class PageSlugController
{
    public function __construct(private SlugGeneratorInterface $slugGenerator)
    {
    }

    public function generateAction(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        Assert::string($name);

        return new JsonResponse([
            'slug' => $this->slugGenerator->generate($name),
        ]);
    }
}
