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

class ContentConfigurationRepository extends EntityRepository implements ContentConfigurationRepositoryInterface
{
    public function findByTypeWithOwners(string $type): array
    {
        return $this->createQueryBuilder('o')
            ->addSelect('page', 'block')
            ->leftJoin('o.page', 'page')
            ->leftJoin('o.block', 'block')
            ->andWhere('o.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult()
        ;
    }
}
