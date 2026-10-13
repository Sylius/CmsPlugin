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

    use FormUiStateComponentTrait {
        FormUiStateComponentTrait::removeCollectionItem insteadof LiveCollectionTrait;
    }

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
    ) {
        $this->initialize($mediaRepository, $formFactory, $resourceClass, $formClass);
    }

    protected function getUiStateTranslationsPaths(): array
    {
        return ['translations'];
    }

    protected function getUiStateElementsPath(): ?string
    {
        return null;
    }
}
