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

namespace Sylius\CmsPlugin\Twig\Component\Media;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\UiState\FormUiStateConfiguration;
use Sylius\CmsPlugin\Form\UiState\FormUiStateResolverInterface;
use Sylius\CmsPlugin\Twig\Component\Trait\FormUiStateComponentTrait;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;

class FormComponent
{
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<MediaInterface> */
    use ResourceFormComponentTrait;

    use FormUiStateComponentTrait;

    /**
     * @param RepositoryInterface<MediaInterface> $mediaRepository
     * @param class-string<MediaInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     */
    public function __construct(
        RepositoryInterface $mediaRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
        FormUiStateResolverInterface $formUiStateResolver,
    ) {
        $this->initialize($mediaRepository, $formFactory, $resourceClass, $formClass);
        $this->initializeUiState($formUiStateResolver, new FormUiStateConfiguration(['translations']));
    }
}
