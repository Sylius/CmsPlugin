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
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementTypeLabels;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

final class TemplateType extends AbstractResourceType
{
    public const DEFAULT_TYPE = 'page';

    /** @var array<string, string> */
    private array $contentElementTypes;

    /**
     * @param class-string $dataClass
     * @param string[] $validationGroups
     * @param iterable<string, mixed> $contentElementTypes
     */
    public function __construct(string $dataClass, array $validationGroups = [], iterable $contentElementTypes = [])
    {
        parent::__construct($dataClass, $validationGroups);

        $this->contentElementTypes = ContentElementTypeLabels::fromTypes($contentElementTypes);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
                'help' => 'sylius_cms.ui.template_form.name_help',
                'attr' => ['placeholder' => 'sylius_cms.ui.template_form.name_placeholder'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
                'choices' => [
                    'sylius_cms.ui.page' => 'page',
                    'sylius_cms.ui.block' => 'block',
                ],
                'expanded' => true,
            ])
            ->add('contentElements', LiveCollectionType::class, [
                'entry_type' => ContentElementType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'button_add_type' => AddButtonType::class,
                'button_add_options' => [
                    'label' => 'sylius_cms.ui.add_element',
                    'types' => $this->contentElementTypes,
                ],
                'button_delete_options' => [
                    'label' => false,
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event): void {
                $template = $event->getData();
                if ($template instanceof TemplateInterface && null === $template->getType()) {
                    $template->setType(self::DEFAULT_TYPE);
                }
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_template';
    }
}
