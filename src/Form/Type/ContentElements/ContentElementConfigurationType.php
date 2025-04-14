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

namespace Sylius\CmsPlugin\Form\Type\ContentElements;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElementChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContentElementConfigurationType extends AbstractResourceType
{
    /** @var array<string, string> */
    private array $elementTypes = [];

    private string $defaultElementType;

    /** @param iterable<string, FormTypeInterface> $actionConfigurationTypes */
    public function __construct(
        string $dataClass,
        array $validationGroups,
        iterable $actionConfigurationTypes,
    ) {
        parent::__construct($dataClass, $validationGroups);

        foreach ($actionConfigurationTypes as $type => $formType) {
            $this->elementTypes[$type] = $formType::class;
        }

        $this->defaultElementType = (string) current($this->elementTypes);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ContentElementChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
            ])
        ;

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $elementType = $this->resolveElementType($event->getForm(), $event->getData());
                if (null === $elementType) {
                    return;
                }

                $this->addElementFields($event->getForm(), $elementType);
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $elementType = $this->resolveElementType($event->getForm(), $event->getData());
                if (null === $elementType) {
                    return;
                }

                $event->getForm()->get('type')->setData($elementType);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!isset($data['type']) || $data['type'] === '') {
                    return;
                }

                $this->addElementFields($event->getForm(), $data['type']);
            })
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['types'] = $options['types'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('types', [])
            ->setDefault('element_type', null)
            ->setAllowedTypes('element_type', ['string', 'null'])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_element_configuration';
    }

    private function resolveElementType(FormInterface $form, mixed $data = null): ?string
    {
        if ($data instanceof ContentConfigurationInterface && null !== $data->getType()) {
            return $data->getType();
        }

        if ($form->getConfig()->hasOption('element_type')) {
            return $form->getConfig()->getOption('element_type');
        }

        return null;
    }

    private function addElementFields(FormInterface $form, string $elementType): void
    {
        $elementFromType = $this->elementTypes[$elementType] ?? $this->elementTypes[$this->defaultElementType] ?? null;
        if (null === $elementFromType) {
            throw new \InvalidArgumentException(sprintf('Element type "%s" does not exist.', $elementType));
        }

        $form->add('configuration', $elementFromType, [
            'label' => false,
        ]);
    }
}
