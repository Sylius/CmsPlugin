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

namespace Sylius\CmsPlugin\Controller;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Sylius\CmsPlugin\Controller\Helper\FormErrorsFlashHelperInterface;

trait MediaPageControllersCommonDependencyInjectionsTrait
{
    private CacheManager $cacheManager;

    private DataManager $dataManager;

    private FormErrorsFlashHelperInterface $formErrorsFlashHelper;

    public function setFormErrorsFlashHelper(FormErrorsFlashHelperInterface $formErrorsFlashHelper): void
    {
        $this->formErrorsFlashHelper = $formErrorsFlashHelper;
    }

    public function setCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }

    public function setDataManager(DataManager $dataManager): void
    {
        $this->dataManager = $dataManager;
    }
}
