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

namespace Tests\Sylius\CmsPlugin\Behat\Behaviour;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Behaviour\DocumentAccessor;

trait ContainsErrorTrait
{
    use DocumentAccessor;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool
    {
        $validationMessageElements = array_merge(
            $this->getDocument()->findAll('css', '.invalid-feedback'),
            $this->getDocument()->findAll('css', '.alert-danger'),
        );

        /** @var NodeElement $validationMessageElement */
        foreach ($validationMessageElements as $validationMessageElement) {
            if (true === $strict && $message === $validationMessageElement->getText()) {
                return true;
            }

            if (false === $strict && strstr($validationMessageElement->getText(), $message)) {
                return true;
            }
        }

        return false;
    }
}
