<?php

declare(strict_types=1);


namespace Sylius\CmsPlugin\Plugin\Installer;

use App\Plugin\Installer\PluginInstallerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
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
        Process::fromShellCommandline('php bin/console sylius:fixtures:load cms -n')->run();
        Process::fromShellCommandline('yarn add trix@^2.0.0 swiper@^11.2.6')->run();
    }
}
