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

namespace Sylius\CmsPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Resource\Model\CodeAwareInterface;

interface BlockInterface extends
    ResourceInterface,
    CodeAwareInterface,
    ToggleableInterface,
    CollectibleInterface,
    ChannelsAwareInterface,
    StaticTemplateAwareInterface,
    ContentElementsAwareInterface,
    ProductsAwareInterface,
    TaxonAwareInterface,
    ProductsInTaxonsAwareInterface,
    BlockTaxonAwareInterface,
    BlockProductAwareInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;
}
