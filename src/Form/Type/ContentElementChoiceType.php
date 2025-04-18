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

namespace Sylius\CmsPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContentElementChoiceType extends AbstractType
{
    /** @var array<string, string> */
    private array $contentElements = [];

    /** @param iterable<string, string> $contentElementTypes */
    public function __construct(iterable $contentElementTypes)
    {
        foreach ($contentElementTypes as $type => $formType) {
            $this->contentElements['sylius_cms.ui.content_elements.type.' . $type] = $type;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'sylius_cms.ui.type',
            'choices' => $this->contentElements,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_element_choice';
    }
}
