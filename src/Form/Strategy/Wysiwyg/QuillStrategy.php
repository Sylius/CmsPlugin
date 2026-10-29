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

use Ehyiah\QuillJsBundle\DTO\Modules\FullScreenModule;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class QuillStrategy extends AbstractWysiwygStrategy
{
    public function getParent(): string
    {
        return QuillType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'quill_options' => QuillGroup::buildWithAllFields(),
            'modules' => [
                new FullScreenModule(),
            ],
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        // data-model="norender|*" doesn't work on individual elements — Live Component
        // treats "*" as a literal model name instead of substituting the field's name
        // attribute. Use the actual full_name so the Quill content is included in the
        // component's formValues when a LiveAction (e.g. moveCollectionItem) fires.
        $view->vars['attr']['data-model'] = 'norender|' . $view->vars['full_name'];
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_plugin_quill_strategy';
    }
}
