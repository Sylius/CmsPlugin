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

namespace Sylius\CmsPlugin\Form\Normalizer;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;

/**
 * @internal
 *
 * @see \Symfony\Bridge\Doctrine\Form\Type\EntityType::configureOptions()
 */
final class TypedQueryBuilderNormalizer
{
    public static function normalize(Options $options, callable|QueryBuilder|null $queryBuilder): QueryBuilder
    {
        if (\is_callable($queryBuilder)) {
            $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']));

            if (null !== $queryBuilder && !$queryBuilder instanceof QueryBuilder) {
                throw new UnexpectedTypeException($queryBuilder, QueryBuilder::class);
            }
        }
        if (null === $queryBuilder) {
            $queryBuilder = $options['em']->getRepository($options['class'])->createQueryBuilder('o');
        }

        $type = self::resolveType($options);
        if (null === $type) {
            return $queryBuilder;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        return $queryBuilder
            ->andWhere($alias . '.type = :resourceType')
            ->setParameter('resourceType', $type)
        ;
    }

    private static function resolveType(Options $options): ?string
    {
        if (isset($options['type'])) {
            return $options['type'];
        }

        if (isset($options['extra_options']['type'])) {
            return $options['extra_options']['type'];
        }

        return null;
    }
}
