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

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MediaTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getAlt(): ?string;

    public function setAlt(?string $alt): void;

    public function getLink(): ?string;

    public function setLink(?string $link): void;
}
