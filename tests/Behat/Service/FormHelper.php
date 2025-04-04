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

namespace Tests\Sylius\CmsPlugin\Behat\Service;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Session;

final class FormHelper
{
    public static function fillHiddenInput(
        Session $session,
        string $id,
        $value,
    ): void {
        try {
            $session->executeScript(
                sprintf(
                    "document.getElementById('%s').value = '%s';",
                    $id,
                    $value,
                ),
            );
        }
        /** @noinspection PhpRedundantCatchClauseInspection */
        catch (UnsupportedDriverActionException $ex) {
            $session->getPage()
                ->find('css', 'input#' . $id)
                ->setValue($value)
            ;
        }
    }
}
