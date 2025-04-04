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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\DocumentElement;
use Behat\MinkExtension\Context\RawMinkContext;

final class TrixWysiwygContext extends RawMinkContext implements Context
{
    /**
     * @Then I should see the Trix WYSIWYG editor initialized
     */
    public function iShouldSeeTheTrixWysiwygEditorInitialized(): void
    {
        $this->getPage()->find('css', 'trix-toolbar')->setValue('test');
    }

    private function getPage(): DocumentElement
    {
        return $this->getSession()->getPage();
    }
}
