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

namespace Sylius\CmsPlugin\Form\Type;

use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\WysiwygStrategyInterface;
use Sylius\CmsPlugin\Resolver\WysiwygStrategyResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class WysiwygType extends AbstractType
{
    private WysiwygStrategyInterface $strategy;

    public function __construct(
        private WysiwygStrategyResolverInterface $strategyResolver,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->strategy->configureOptions($resolver);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $this->strategy->buildView($view, $form, $options);
    }

    public function getParent(): string
    {
        return $this->strategy->getParent();
    }

    public function getBlockPrefix(): string
    {
        return $this->strategy->getBlockPrefix();
    }

    public function setStrategy(string $strategy): void
    {
        $this->strategy = $this->strategyResolver->getStrategy($strategy);
    }
}
