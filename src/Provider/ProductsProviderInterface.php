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

namespace Sylius\CmsPlugin\Provider;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductsProviderInterface
{
    /**
     * @param string[] $productCodes
     *
     * @return ProductInterface[]
     */
    public function getProductsByCodes(array $productCodes, ChannelInterface $channel): array;

    /** @return ProductInterface[] */
    public function getProductsByTaxonCode(string $taxonCode, ChannelInterface $channel): array;
}
