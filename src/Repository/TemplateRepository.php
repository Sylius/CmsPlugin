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

namespace Sylius\CmsPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\CmsPlugin\Entity\TemplateInterface;

/**
 * @implements TemplateRepositoryInterface<TemplateInterface>
 */
class TemplateRepository extends EntityRepository implements TemplateRepositoryInterface
{
    public function findTemplatesByNamePart(string $phrase, string $type): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :name')
            ->andWhere('o.type = :type')
            ->setParameters([
                'name' => '%' . $phrase . '%',
                'type' => $type,
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
