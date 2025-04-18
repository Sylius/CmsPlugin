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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class CollectionType extends AbstractResourceType
{
    public const PAGE = 'page';

    public const BLOCK = 'block';

    public const MEDIA = 'media';

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
            ->add('type', ChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
                'choices' => [
                    'sylius_cms.ui.page' => self::PAGE,
                    'sylius_cms.ui.block' => self::BLOCK,
                    'sylius_cms.ui.media' => self::MEDIA,
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event): void {
                $formData = $event->getData();
                switch ($formData['type']) {
                    case self::PAGE:
                        unset($formData['blocks'], $formData['media']);

                        break;
                    case self::BLOCK:
                        unset($formData['pages'], $formData['media']);

                        break;
                    case self::MEDIA:
                        unset($formData['pages'], $formData['blocks']);

                        break;
                }

                $event->setData($formData);
            });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $data = $event->getData();
            if (null === $data) {
                return;
            }

            $type = $data->getType() ?? self::PAGE;

            $this->addContentField($event->getForm(), $type);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            if (!isset($data['type'])) {
                return;
            }

            $this->addContentField($event->getForm(), $data['type']);
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_collection';
    }

    private function addContentField(FormInterface $form, ?string $type): void
    {
        switch ($type) {
            case self::PAGE:
                $form->add('pages', PageAutocompleteType::class, [
                    'multiple' => true,
                    'required' => false,
                ]);

                break;
            case self::BLOCK:
                $form->add('blocks', BlockAutocompleteType::class, [
                    'multiple' => true,
                    'required' => false,
                ]);

                break;
            case self::MEDIA:
                $form->add('media', MediaAutocompleteType::class, [
                    'multiple' => true,
                    'required' => false,
                ]);

                break;
        }
    }
}
