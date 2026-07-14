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

namespace Tests\Sylius\CmsPlugin\Behat\Element\Admin;

use Sylius\Behat\Element\SyliusElement;

final class QuillEditorElement extends SyliusElement implements QuillEditorElementInterface
{
    public function isInitialized(): bool
    {
        $this->getSession()->wait(1000, "document.querySelector('.ql-toolbar') !== null");

        return $this->hasElement('toolbar');
    }

    /** @return array<string, string> */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'toolbar' => '.ql-toolbar',
        ]);
    }
}
