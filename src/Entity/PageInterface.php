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
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\CodeAwareInterface;

interface PageInterface extends
    ResourceInterface,
    CodeAwareInterface,
    TranslatableInterface,
    ToggleableInterface,
    CollectibleInterface,
    TimestampableInterface,
    ChannelsAwareInterface,
    StaticTemplateAwareInterface,
    SlugAwareInterface,
    ContentElementsAwareInterface,
    TeaserInterface
{
    public function getMetaKeywords(): ?string;

    public function setMetaKeywords(?string $metaKeywords): void;

    public function getMetaDescription(): ?string;

    public function setMetaDescription(?string $metaDescription): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getTitle(): ?string;

    public function setTitle(?string $title): void;
}
