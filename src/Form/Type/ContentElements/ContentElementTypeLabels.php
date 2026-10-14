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

namespace Sylius\CmsPlugin\Form\Type\ContentElements;

/** @internal */
final class ContentElementTypeLabels
{
    public const PREFIX = 'sylius_cms.ui.content_elements.type.';

    /**
     * @param iterable<string, mixed> $contentElementTypes the `sylius_cms.content_elements.type` tagged services, indexed by type
     *
     * @return array<string, string> translation keys of the labels, indexed by type
     */
    public static function fromTypes(iterable $contentElementTypes): array
    {
        $labels = [];
        foreach ($contentElementTypes as $type => $formType) {
            $labels[$type] = self::PREFIX . $type;
        }

        return $labels;
    }
}
