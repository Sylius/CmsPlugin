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

use Sylius\CmsPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class TextareaContentElementType extends AbstractType
{
    public const TYPE = 'textarea';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::TYPE, WysiwygType::class, [
                'label' => 'sylius_cms.ui.content_elements.type.' . self::TYPE,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_elements_' . self::TYPE;
    }
}
