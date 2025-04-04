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

namespace Sylius\CmsPlugin\Importer;

interface ImporterInterface
{
    public function import(array $row): void;

    public function getResourceCode(): string;

    public function cleanup(): void;
}
