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

namespace Sylius\CmsPlugin\Form\Strategy\Wysiwyg;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class TrixStrategy extends AbstractWysiwygStrategy
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['block_prefix'] = 'sylius_cms_plugin_trix_strategy';
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_plugin_trix_strategy';
    }
}
