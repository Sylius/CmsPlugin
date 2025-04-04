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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface WysiwygStrategyInterface
{
    public function configureOptions(OptionsResolver $resolver): void;

    public function buildView(FormView $view, FormInterface $form, array $options): void;

    public function getParent(): string;

    public function getBlockPrefix(): string;
}
