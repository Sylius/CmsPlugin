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

use Sylius\Bundle\AdminBundle\Form\Type\AddButtonType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\CmsPlugin\Form\Type\Translation\PageTranslationType;
use Sylius\CmsPlugin\Provider\ResourceTemplateProviderInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

final class PageType extends AbstractResourceType
{
    /** @var array<string, string|null> */
    private array $locales = [];

    /** @param RepositoryInterface<LocaleInterface> $localeRepository */
    public function __construct(
        private RepositoryInterface $localeRepository,
        private ResourceTemplateProviderInterface $templateProvider,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);

        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            $this->locales[$locale->getName()] = $locale->getCode();
        }
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
            ->add('templates', ChoiceType::class, [
                'label' => 'sylius_cms.ui.template',
                'choices' => $this->templateProvider->getPageTemplates(),
                'mapped' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_cms.ui.enabled',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'sylius_cms.ui.images',
                'entry_type' => PageTranslationType::class,
            ])
            ->add('collections', CollectionAutocompleteChoiceType::class, [
                'label' => 'sylius_cms.ui.collections',
                'multiple' => true,
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
            ->add('contentTemplate', TemplatePageAutocompleteChoiceType::class, [
                'label' => 'sylius_cms.ui.content_elements.template',
                'mapped' => false,
            ])
            ->add('contentElements', LiveCollectionType::class, [
                'entry_type' => ContentConfigurationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'content-elements-container',
                ],
            ])
            ->add('locale', ChoiceType::class, [
                'choices' => $this->locales,
                'mapped' => false,
                'label' => 'sylius.ui.locale',
                'attr' => [
                    'class' => 'locale-selector',
                ],
            ])
        ;

        self::addContentElementLocaleListener($builder);
        self::addTemplateListener($builder);
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
