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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\ContentConfiguration;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Tests\Sylius\CmsPlugin\Behat\Helpers\ContentElementHelper;
use Tests\Sylius\CmsPlugin\Behat\Service\RandomStringGeneratorInterface;

final class BlockContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private RandomStringGeneratorInterface $randomStringGenerator,
        private FactoryInterface $blockFactory,
        private BlockRepositoryInterface $blockRepository,
    ) {
    }

    /**
     * @Given there is a dynamic content block
     * @Given there is a block in the store
     */
    public function thereIsADynamicContentBlock(): void
    {
        $block = $this->createBlock();

        $this->saveBlock($block);
    }

    /**
     * @Given there is a block :name
     * @Given there is a block :name with code :code
     */
    public function thereIsABlockWithCodeAndContent(string $name, ?string $code = null): void
    {
        $block = $this->createBlock($name, $code);

        $this->saveBlock($block);
    }

    /**
     * @Given there is a block :name with :contentElement content element
     */
    public function thereIsABlockWithContentElement(string $code, string $contentElement): void
    {
        $block = $this->createBlockWithContentElement($code, $contentElement);

        $this->saveBlock($block);
    }

    private function createBlock(
        ?string $name = null,
        ?string $code = null,
        ?ChannelInterface $channel = null,
    ): BlockInterface {
        /** @var BlockInterface $block */
        $block = $this->blockFactory->createNew();

        if (null === $name) {
            $name = $this->randomStringGenerator->generate();
        }
        if (null === $code) {
            $code = StringInflector::nameToLowercaseCode($name);
        }
        if (null === $channel && $this->sharedStorage->has('channel')) {
            $channel = $this->sharedStorage->get('channel');
        }

        $block->setName($name);
        $block->setCode($code);
        $block->addChannel($channel);

        return $block;
    }

    private function createBlockWithContentElement(string $name, string $contentElement): BlockInterface
    {
        $block = $this->createBlock($name);

        /** @var ContentConfigurationInterface $contentConfiguration */
        $contentConfiguration = new ContentConfiguration();
        $contentConfiguration->setType(mb_strtolower($contentElement));
        $contentConfiguration->setLocale('en_US');
        $contentConfiguration->setConfiguration(ContentElementHelper::getExampleConfigurationByContentElement($contentElement));
        $contentConfiguration->setBlock($block);

        $block->addContentElement($contentConfiguration);

        return $block;
    }

    private function saveBlock(BlockInterface $block): void
    {
        $this->blockRepository->add($block);
        $this->sharedStorage->set('block', $block);
    }
}
