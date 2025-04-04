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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Media;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsEmptyListInterface;

interface IndexPageInterface extends BaseIndexPageInterface, ContainsEmptyListInterface
{
    public function deleteMedia(string $code): void;
}
