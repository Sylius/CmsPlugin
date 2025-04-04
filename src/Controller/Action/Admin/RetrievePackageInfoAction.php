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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RetrievePackageInfoAction
{
    public function __invoke(Request $request): Response
    {
        try {
            file_get_contents(\sprintf(
                "https://intranet.bitbag.shop/retrieve-package-info?packageName='%s'&url='%s'",
                'bitbag/cms-plugin',
                \sprintf('%s://%s', $request->getScheme(), $request->getHttpHost()),
            ));
        } catch (\Exception) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        return new Response();
    }
}
