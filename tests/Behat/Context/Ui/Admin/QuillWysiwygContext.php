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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\Sylius\CmsPlugin\Behat\Element\Admin\QuillEditorElementInterface;
use Webmozart\Assert\Assert;

final class QuillWysiwygContext implements Context
{
    public function __construct(
        private readonly QuillEditorElementInterface $quillEditor,
    ) {
    }

    /**
     * @Then I should see the Quill WYSIWYG editor initialized
     */
    public function iShouldSeeTheQuillWysiwygEditorInitialized(): void
    {
        Assert::true($this->quillEditor->isInitialized(), 'Quill WYSIWYG editor is not initialized.');
    }
}
