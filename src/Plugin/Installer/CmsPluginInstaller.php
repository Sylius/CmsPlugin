<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);


namespace Sylius\CmsPlugin\Plugin\Installer;

use App\Plugin\Installer\PluginInstallerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Process\Process;

#[AsTaggedItem('app.plugin_installer')]
class CmsPluginInstaller implements PluginInstallerInterface
{
    public function supports(string $packageName): bool
    {
        return $packageName === 'sylius/cms-plugin';
    }

    public function install(string $version): void
    {
        Process::fromShellCommandline('yarn add trix@^2.0.0 swiper@^11.2.6')->run();
    }
}
