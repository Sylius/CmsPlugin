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

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ChecksCodeImmutabilityTrait;
use Webmozart\Assert\Assert;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutabilityTrait;

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function uploadFile(string $file): void
    {
        $path = __DIR__ . '/../../../Resources/media/' . $file;

        Assert::fileExists($path);

        $this->getDocument()->attachFileToField('Choose file', $path);
    }
}
