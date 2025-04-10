<?php

declare(strict_types=1);

namespace Sylius\CmsPlugin\Form\Normalizer;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;

/**
 * @internal
 * @see \Symfony\Bridge\Doctrine\Form\Type\EntityType::configureOptions()
 */
final class TypedQueryBuilderNormalizer
{
    public static function normalize(Options $options, QueryBuilder|callable|null $queryBuilder): QueryBuilder
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
        if (false === isset($options['type'])) {
            return $queryBuilder;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        return $queryBuilder
            ->andWhere($alias . '.type = :resourceType')
            ->setParameter('resourceType', $options['type'])
        ;
    }
}
