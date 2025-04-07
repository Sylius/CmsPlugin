<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Form\DataTransformer;

use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class ContentElementDataTransformerChecker
{
    /** @param RepositoryInterface<covariant ResourceInterface> $repository */
    public function check(FormBuilderInterface $builder, RepositoryInterface $repository, string $field): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($repository, $field): void {
            $data = $event->getData();
            $code = $data[$field] ?? null;
            $entity = $repository->findOneBy(['code' => $code]);
            if (null === $entity) {
                $data[$field] = null;
                $event->setData($data);
            }
        });
    }
}
