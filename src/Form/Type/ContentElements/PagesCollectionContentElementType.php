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

use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Form\DataTransformer\ContentElementDataTransformerChecker;
use Sylius\CmsPlugin\Form\Type\CollectionAutocompleteChoiceType;
use Sylius\CmsPlugin\Form\Type\CollectionType;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;

final class PagesCollectionContentElementType extends AbstractType
{
    public const TYPE = 'pages_collection';

    /** @param RepositoryInterface<CollectionInterface> $collectionRepository */
    public function __construct(
        private RepositoryInterface $collectionRepository,
        private ContentElementDataTransformerChecker $contentElementDataTransformerChecker,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::TYPE, CollectionAutocompleteChoiceType::class, [
                'label' => 'sylius_cms.ui.content_elements.type.' . self::TYPE,
                'type' => CollectionType::PAGE,
                'extra_options' => [
                    'type' => CollectionType::PAGE,
                ],
            ])
        ;

        $builder->get(self::TYPE)->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->collectionRepository, 'code')),
        );

        $this->contentElementDataTransformerChecker->check($builder, $this->collectionRepository, self::TYPE);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_elements_' . self::TYPE;
    }
}
