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

use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class TemplateFixtureFactory implements FixtureFactoryInterface
{
    /** @param RepositoryInterface<TemplateInterface> $templateRepository */
    public function __construct(
        private FactoryInterface $templateFactory,
        private RepositoryInterface $templateRepository,
    ) {
    }

    public function load(array $data): void
    {
        foreach ($data as $fields) {
            /** @var ?TemplateInterface $template */
            $template = $this->templateRepository->findOneBy(['name' => $fields['name']]);
            if (
                true === $fields['remove_existing'] &&
                null !== $template
            ) {
                $this->templateRepository->remove($template);
            }

            $this->createPage($fields);
        }
    }

    /** @param array<string, mixed> $pageData */
    private function createPage(array $pageData): void
    {
        /** @var TemplateInterface $template */
        $template = $this->templateFactory->createNew();

        $template->setName($pageData['name']);
        $template->setType($pageData['type']);
        $template->setContentElements($pageData['content_elements']);

        $this->templateRepository->add($template);
    }
}
