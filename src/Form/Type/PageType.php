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

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\CmsPlugin\Form\Type\Translation\ContentConfigurationTranslationsType;
use Sylius\CmsPlugin\Form\Type\Translation\PageTranslationType;
use Sylius\CmsPlugin\Provider\ResourceTemplateProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class PageType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private ResourceTemplateProviderInterface $templateProvider,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius_cms.ui.code',
                'disabled' => null !== $builder->getData()->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'sylius_cms.ui.template',
                'choices' => $this->templateProvider->getPageTemplates(),
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_cms.ui.enabled',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'sylius_cms.ui.images',
                'entry_type' => PageTranslationType::class,
            ])
            ->add('collections', CollectionAutocompleteType::class, [
                'label' => 'sylius_cms.ui.collections',
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'sylius_cms.ui.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('publishAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'sylius_cms.ui.publish_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('contentElements', ContentConfigurationTranslationsType::class, [
                'entry_type' => ContentConfigurationType::class,
                'by_reference' => false,
            ])
        ;
    }

    public static function addContentElementLocaleListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $selectedLocale = $data['locale'] ?? null;

            if (isset($data['contentElements'])) {
                foreach ($data['contentElements'] as &$contentElement) {
                    if (!isset($contentElement['locale']) || '' === $contentElement['locale']) {
                        $contentElement['locale'] = $selectedLocale;
                    }
                }
            }

            $event->setData($data);
        });
    }

    public static function addTemplateListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $template = $data['templates'] ?? null;

            $entity = $form->getData();
            $entity->setTemplate($template);
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $template = $data->getTemplate();

            $form->get('templates')->setData($template);
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_admin_page';
    }
}
