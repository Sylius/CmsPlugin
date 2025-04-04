<?php

declare(strict_types=1);

namespace Sylius\CmsPlugin\Form\Type;

use BitBag\SyliusCmsPlugin\Form\Strategy\Wysiwyg\WysiwygStrategyInterface;
use BitBag\SyliusCmsPlugin\Resolver\WysiwygStrategyResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class WysiwygType extends AbstractType
{
    private WysiwygStrategyInterface $strategy;

    public function __construct(private WysiwygStrategyResolverInterface $strategyResolver)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->strategy->configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'sylius_cms.ui.content',
            'config' => [
                'filebrowserUploadUrl' => $this->urlGenerator->generate('sylius_cms_admin_upload_editor_image'),
                'bodyId' => 'cms-ckeditor',
            ],
        ]);
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
