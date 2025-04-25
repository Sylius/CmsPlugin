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

namespace Sylius\CmsPlugin\Twig\Component\Page;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Sylius\CmsPlugin\Twig\Component\Trait\ContentElementsCollectionFormComponentTrait;
use Sylius\CmsPlugin\Twig\Component\Trait\PreviewComponentTrait;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Twig\Environment;

class FormComponent
{
    use ComponentToolsTrait;
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<PageInterface> */
    use ResourceFormComponentTrait;

    use ContentElementsCollectionFormComponentTrait;
    use PreviewComponentTrait;

    /**
     * @param RepositoryInterface<PageInterface> $pageRepository
     * @param class-string<PageInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     * @param TemplateRepositoryInterface<TemplateInterface> $templateRepository
     */
    public function __construct(
        RepositoryInterface $pageRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
        TemplateRepositoryInterface $templateRepository,
        Environment $twig,
        string $previewTemplate,
        protected readonly SlugGeneratorInterface $slugGenerator,
    ) {
        $this->initialize($pageRepository, $formFactory, $resourceClass, $formClass);
        $this->initializeTemplateRepository($templateRepository);
        $this->initializePreview($twig, $previewTemplate);
    }

    #[LiveAction]
    public function generateSlug(#[LiveArg] string $localeCode): void
    {
        $this->formValues['translations'][$localeCode]['slug'] = $this->slugGenerator->generate(
            $this->formValues['name'],
        );
    }
}
