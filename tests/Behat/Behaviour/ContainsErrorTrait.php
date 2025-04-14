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
        $validationMessageElements = $this->getDocument()->findAll('css', '.sylius-validation-error, .invalid-feedback');

        foreach ($validationMessageElements as $element) {
            $text = trim($element->getText());

            if ($strict && $text === $message) {
                return true;
            }

            if (!$strict && str_contains($text, $message)) {
                return true;
            }
        }

        return false;
    }
}
