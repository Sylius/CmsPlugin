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

namespace Tests\Sylius\CmsPlugin\Functional;

use ApiTestCase\JsonApiTestCase as BaseJsonApiTestCase;

abstract class FunctionalTestCase extends BaseJsonApiTestCase
{
    protected string $filesPath;

    public function __construct(
        ?string $name = null,
        array $data = [],
        string $dataName = '',
    ) {
        parent::__construct($name, $data, $dataName);

        $this->dataFixturesPath = __DIR__ . \DIRECTORY_SEPARATOR . 'DataFixtures' . \DIRECTORY_SEPARATOR . 'ORM';
        $this->expectedResponsesPath = __DIR__ . \DIRECTORY_SEPARATOR . 'Responses' . \DIRECTORY_SEPARATOR . 'Expected';
        $this->filesPath = __DIR__ . \DIRECTORY_SEPARATOR . 'Resources' . \DIRECTORY_SEPARATOR . 'files';
    }

    public function getFilePath(string $fileName): string
    {
        return $this->filesPath . \DIRECTORY_SEPARATOR . $fileName;
    }
}
