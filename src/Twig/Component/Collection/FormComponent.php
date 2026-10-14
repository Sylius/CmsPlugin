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

namespace Sylius\CmsPlugin\Twig\Component\Collection;

use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\PagesCollectionContentElementType;
use Sylius\CmsPlugin\Repository\ContentConfigurationRepositoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

class FormComponent
{
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<CollectionInterface> */
    use ResourceFormComponentTrait;

    public const ELEMENTS_PER_PAGE = 8;

    #[LiveProp]
    public int $elementsPage = 1;

    /** @var list<array{type: 'page'|'block', resource: PageInterface|BlockInterface}>|null */
    private ?array $usages = null;

    /**
     * @param RepositoryInterface<CollectionInterface> $collectionRepository
     * @param class-string<CollectionInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     */
    public function __construct(
        RepositoryInterface $collectionRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
        private readonly ContentConfigurationRepositoryInterface $contentConfigurationRepository,
    ) {
        $this->initialize($collectionRepository, $formFactory, $resourceClass, $formClass);
    }

    #[LiveAction]
    public function changeElementsPage(#[LiveArg] int $page): void
    {
        $this->elementsPage = max(1, $page);
    }

    /**
     * Pages and blocks rendering this collection with the "Pages collection" content element.
     *
     * @return list<array{type: 'page'|'block', resource: PageInterface|BlockInterface}>
     */
    public function getUsages(): array
    {
        if (null !== $this->usages) {
            return $this->usages;
        }

        $code = $this->resource?->getCode();
        if (null === $this->resource?->getId() || null === $code) {
            return $this->usages = [];
        }

        $usages = [];
        /** @var ContentConfigurationInterface $element */
        foreach ($this->contentConfigurationRepository->findByTypeWithOwners(PagesCollectionContentElementType::TYPE) as $element) {
            if ($code !== ($element->getConfiguration()[PagesCollectionContentElementType::TYPE] ?? null)) {
                continue;
            }

            $page = $element->getPage();
            if (null !== $page) {
                $usages['page_' . $page->getId()] = ['type' => 'page', 'resource' => $page];

                continue;
            }

            $block = $element->getBlock();
            if (null !== $block) {
                $usages['block_' . $block->getId()] = ['type' => 'block', 'resource' => $block];
            }
        }

        return $this->usages = array_values($usages);
    }
}
