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

namespace Tests\Sylius\CmsPlugin\Behat\Service;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use Webmozart\Assert\Assert;

final class WysiwygHelper
{
    public static function fillContent(
        Session $session,
        DocumentElement $document,
        string $content,
        int $iframeNumber = 1,
    ): void {
        Assert::isInstanceOf($session->getDriver(), ChromeDriver::class);

        $session->wait(3000);
        $session->switchToIFrame($iframeNumber);

        $document->find('css', '#cms-ckeditor')->setValue($content);

        $session->switchToIFrame(null);
    }
}
