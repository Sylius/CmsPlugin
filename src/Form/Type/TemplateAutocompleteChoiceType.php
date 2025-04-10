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

namespace Sylius\CmsPlugin\Form\Type;

use Doctrine\ORM\QueryBuilder;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

final class TemplateAutocompleteChoiceType extends AbstractType
{
    public function __construct(protected readonly string $templateClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['page', 'block']);
        $resolver->setDefaults([
            'class' => $this->templateClass,
            'choice_name' => 'name',
            'choice_value' => 'id',
            'choice_label' => fn (TemplateInterface $template): string => (string) $template->getName(),
        ]);
        $resolver->setNormalizer(
            'query_builder',
            function (Options $options, QueryBuilder|callable|null $queryBuilder): QueryBuilder {
                if (\is_callable($queryBuilder)) {
                    $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']));

                    if (null !== $queryBuilder && !$queryBuilder instanceof QueryBuilder) {
                        throw new UnexpectedTypeException($queryBuilder, QueryBuilder::class);
                    }
                }
                if (null === $queryBuilder) {
                    $queryBuilder = $options['em']->getRepository($options['class'])->createQueryBuilder('o');
                }

                $alias = $queryBuilder->getRootAliases()[0];

                return $queryBuilder
                    ->andWhere($alias . '.type = :templateType')
                    ->setParameter('templateType', $options['type'])
                ;
            },
        );
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
