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

use Sylius\Bundle\AdminBundle\Form\Type\ProductAutocompleteType;
use Sylius\Bundle\AdminBundle\Form\Type\TaxonAutocompleteType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Provider\ResourceTemplateProviderInterface;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

final class BlockType extends AbstractResourceType
{
    /** @var array<string, string|null> */
    private array $locales = [];

    /**
     * @param RepositoryInterface<LocaleInterface> $localeRepository
     * @param TemplateRepositoryInterface<TemplateInterface> $templateRepository
     */
    public function __construct(
        private RepositoryInterface $localeRepository,
        private ResourceTemplateProviderInterface $templateProvider,
        private TemplateRepositoryInterface $templateRepository,
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
        /** @var BlockInterface $block */
        $block = $builder->getData();

        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius_cms.ui.code',
                'disabled' => null !== $block->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
            ])
            ->add('templates', ChoiceType::class, [
                'label' => 'sylius_cms.ui.template',
                'choices' => $this->templateProvider->getBlockTemplates(),
                'mapped' => false,
            ])
            ->add('collections', CollectionAutocompleteChoiceType::class, [
                'label' => 'sylius_cms.ui.collections',
                'multiple' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_cms.ui.enabled',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'sylius_cms.ui.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
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
            ->add('products', ProductAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_products.label',
                'multiple' => true,
                'help' => 'sylius_cms.ui.display_for_products.help',
            ])
            ->add('productsInTaxons', TaxonAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_products_in_taxons.label',
                'multiple' => true,
                'help' => 'sylius_cms.ui.display_for_products_in_taxons.help',
            ])
            ->add('taxons', TaxonAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_taxons.label',
                'multiple' => true,
                'help' => 'sylius_cms.ui.display_for_taxons.help',
            ])
            ->add('contentTemplate', TemplateBlockAutocompleteChoiceType::class, [
                'mapped' => false,
                'multiple' => false,
            ])
            ->add('locale', ChoiceType::class, [
                'choices' => $this->locales,
                'mapped' => false,
                'label' => 'sylius.ui.locale',
                'attr' => [
                    'class' => 'locale-selector',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!isset($data['contentTemplate']) || $data['contentTemplate'] === '') {
                    return;
                }

                $templateId = (int) $data['contentTemplate'];

                /** @return TemplateInterface|null */
                $template = $this->templateRepository->find($templateId);

                if ($template === null || $template->getContentElements() === []) {
                    return;
                }

                $templateElementTypes = array_map(
                    static fn (array $element) => $element['type'],
                    $template->getContentElements(),
                );

                $existingElementTypes = array_map(
                    static fn (array $element) => $element['type'] ?? null,
                    $data['contentElements'] ?? [],
                );

                $allTypesExist = count(array_diff($templateElementTypes, $existingElementTypes)) === 0;
                if ($allTypesExist) {
                    return;
                }

                $data['contentElements'] = [];

                foreach ($template->getContentElements() as $element) {
                    $data['contentElements'][] = [
                        'type' => $element['type'],
                        'configuration' => [],
                        'locale' => $data['locale'] ?? 'en_US',
                    ];
                }

                $event->setData($data);
            })
        ;

        PageType::addContentElementLocaleListener($builder);
        PageType::addTemplateListener($builder);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_block';
    }
}
