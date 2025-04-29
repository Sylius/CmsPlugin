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

namespace Sylius\CmsPlugin\Fixture\Factory;

use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\ContentConfiguration;
use Sylius\CmsPlugin\Fixture\Assigner\ChannelsAssignerInterface;
use Sylius\CmsPlugin\Fixture\Assigner\CollectionsAssignerInterface;
use Sylius\CmsPlugin\Fixture\Assigner\ProductsAssignerInterface;
use Sylius\CmsPlugin\Fixture\Assigner\ProductsInTaxonsAssignerInterface;
use Sylius\CmsPlugin\Fixture\Assigner\TaxonsAssignerInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class BlockFixtureFactory implements FixtureFactoryInterface
{
    public function __construct(
        private FactoryInterface $blockFactory,
        private BlockRepositoryInterface $blockRepository,
        private CollectionsAssignerInterface $collectionsAssigner,
        private ChannelsAssignerInterface $channelAssigner,
        private ProductsAssignerInterface $productsAssigner,
        private TaxonsAssignerInterface $taxonsAssigner,
        private ProductsInTaxonsAssignerInterface $productsInTaxonsAssigner,
    ) {
    }

    public function load(array $data): void
    {
        foreach ($data as $code => $fields) {
            /** @var ?BlockInterface $block */
            $block = $this->blockRepository->findOneBy(['code' => $code]);
            if (
                true === $fields['remove_existing'] &&
                null !== $block
            ) {
                $this->blockRepository->remove($block);
            }

            $this->createBlock($code, $fields);
        }
    }

    /** @param array<string, mixed> $blockData */
    private function createBlock(string $code, array $blockData): void
    {
        /** @var BlockInterface $block */
        $block = $this->blockFactory->createNew();

        $block->setCode($code);
        $block->setName($blockData['name']);
        $block->setEnabled($blockData['enabled']);

        $this->collectionsAssigner->assign($block, $blockData['collections']);
        $this->channelAssigner->assign($block, $blockData['channels']);
        $this->productsAssigner->assign($block, $blockData['products']);
        $this->taxonsAssigner->assign($block, $blockData['taxons']);
        $this->productsInTaxonsAssigner->assign($block, $blockData['products_in_taxons']);

        foreach ($blockData['content_elements'] as $locale => $data) {
            foreach ($data as $contentElementData) {
                $contentElementData['data'] = array_filter($contentElementData['data'], static function ($value) {
                    return null !== $value && '' !== $value;
                });

                $contentConfiguration = new ContentConfiguration();
                $contentConfiguration->setType($contentElementData['type']);
                $contentConfiguration->setConfiguration($contentElementData['data']);
                $contentConfiguration->setLocale($locale);
                $contentConfiguration->setBlock($block);
                $block->addContentElement($contentConfiguration);
            }
        }

        $this->blockRepository->add($block);
    }
}
