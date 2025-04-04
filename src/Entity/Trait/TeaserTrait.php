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

namespace Sylius\CmsPlugin\Entity\Trait;

use Sylius\CmsPlugin\Entity\MediaInterface;

trait TeaserTrait
{
    protected ?string $teaserTitle = null;

    protected ?string $teaserContent = null;

    protected ?MediaInterface $teaserImage = null;

    public function getTeaserTitle(): ?string
    {
        return $this->teaserTitle;
    }

    public function setTeaserTitle(?string $teaserTitle): void
    {
        $this->teaserTitle = $teaserTitle;
    }

    public function getTeaserContent(): ?string
    {
        return $this->teaserContent;
    }

    public function setTeaserContent(?string $teaserContent): void
    {
        $this->teaserContent = $teaserContent;
    }

    public function getTeaserImage(): ?MediaInterface
    {
        return $this->teaserImage;
    }

    public function setTeaserImage(?MediaInterface $teaserImage): void
    {
        $this->teaserImage = $teaserImage;
    }
}
