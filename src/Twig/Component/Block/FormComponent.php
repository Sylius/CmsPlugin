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

namespace Sylius\CmsPlugin\Twig\Component\Block;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Sylius\CmsPlugin\Twig\Component\Trait\ContentElementsCollectionFormComponentTrait;
use Sylius\CmsPlugin\Twig\Component\Trait\PreviewComponentTrait;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Twig\Environment;

class FormComponent
{
    use ComponentToolsTrait;
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<BlockInterface> */
    use ResourceFormComponentTrait;

    use ContentElementsCollectionFormComponentTrait;
    use PreviewComponentTrait;

    /**
     * @param RepositoryInterface<BlockInterface> $blockRepository
     * @param class-string<BlockInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     * @param TemplateRepositoryInterface<TemplateInterface> $templateRepository
     */
    public function __construct(
        RepositoryInterface $blockRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
        TemplateRepositoryInterface $templateRepository,
        Environment $twig,
        LocaleProviderInterface $localeProvider,
        string $previewTemplate,
        protected readonly ContentElementRendererStrategyInterface $contentElementRendererStrategy,
    ) {
        $this->initialize($blockRepository, $formFactory, $resourceClass, $formClass);
        $this->initializeTemplateRepository($templateRepository);
        $this->initializePreview($twig, $localeProvider, $previewTemplate);
    }

    /** @return array{resource: BlockInterface|null, content: string} */
    protected function getRenderParameters(): array
    {
        if (null === $this->resource) {
            return [
                'resource' => null,
                'content' => '',
            ];
        }

        return [
            'resource' => $this->resource,
            'content' => $this->contentElementRendererStrategy->render($this->resource),
        ];
    }
}
