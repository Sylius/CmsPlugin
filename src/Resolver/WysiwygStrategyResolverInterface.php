<?php

declare(strict_types=1);

namespace Sylius\CmsPlugin\Resolver;

use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\WysiwygStrategyInterface;

interface WysiwygStrategyResolverInterface
{
    public function getStrategy(string $wysiwygType): WysiwygStrategyInterface;
}
