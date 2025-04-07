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

namespace Sylius\CmsPlugin\Importer;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractImporter implements ImporterInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function cleanup(): void
    {
    }

    /** @param array<string> $row */
    protected function getColumnValue(string $column, array $row): ?string
    {
        return $row[$column] ?? null;
    }

    /** @param array<string> $row */
    protected function getTranslatableColumnValue(
        string $column,
        string $locale,
        array $row,
    ): ?string {
        $column = str_replace('__locale__', '_' . $locale, $column);

        return $row[$column] ?? null;
    }

    /**
     * @param array<string> $translatableColumns
     * @param array<string> $columns
     *
     * @return array<string>
     */
    protected function getAvailableLocales(array $translatableColumns, array $columns): array
    {
        $locales = [];
        foreach ($translatableColumns as $translatableColumn) {
            $translatableColumn = str_replace('__locale__', '_', $translatableColumn);
            foreach ($columns as $column) {
                if (str_starts_with($column, $translatableColumn)) {
                    $localePart = substr($column, strlen($translatableColumn));

                    if (1 === preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $localePart)) {
                        $locales[] = $localePart;
                    }
                }
            }
        }

        return array_unique($locales);
    }

    /** @param array<string> $groups */
    protected function validateResource(ResourceInterface $resource, array $groups): void
    {
        $errors = $this->validator->validate($resource, null, $groups);

        if (0 < count($errors)) {
            $message = '';

            foreach ($errors as $error) {
                $message .= lcfirst(rtrim((string) $error->getMessage(), '.')) . ', ';
            }

            $message = ucfirst(rtrim($message, ', ')) . '.';

            throw new \RuntimeException($message);
        }
    }
}
