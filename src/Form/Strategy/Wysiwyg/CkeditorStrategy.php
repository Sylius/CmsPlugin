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

namespace Sylius\CmsPlugin\Form\Strategy\Wysiwyg;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CkeditorStrategy extends AbstractWysiwygStrategy
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'sylius_cms_plugin.ui.content',
            'config' => [
                'filebrowserUploadUrl' => $this->urlGenerator->generate('sylius_cms_admin_upload_editor_image'),
                'bodyId' => 'bitbag-ckeditor',
            ],
        ]);
    }

    public function getParent(): string
    {
        return CKEditorType::class;
    }
}
