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

namespace Sylius\CmsPlugin\Twig\Component\Template;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Twig\Component\Trait\CollectionItemsFormComponentTrait;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;

class FormComponent
{
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<TemplateInterface> */
    use ResourceFormComponentTrait;

    use CollectionItemsFormComponentTrait;

    /**
     * @param RepositoryInterface<TemplateInterface> $templateRepository
     * @param class-string<TemplateInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     */
    public function __construct(
        RepositoryInterface $templateRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
    ) {
        $this->initialize($templateRepository, $formFactory, $resourceClass, $formClass);
    }
}
