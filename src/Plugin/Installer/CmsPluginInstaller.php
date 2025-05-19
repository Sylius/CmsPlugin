<?php

declare(strict_types=1);


namespace Sylius\CmsPlugin\Plugin\Installer;

use App\Plugin\Installer\PluginInstallerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\Process\Process;

#[AsTaggedItem('app.plugin_installer')]
class CmsPluginInstaller implements PluginInstallerInterface
{
    public function supports(string $packageName): bool
    {
        return $packageName === 'sylius/cms-plugin';
    }

    public function install(SymfonyStyle $io): void
    {
        $io->title('Installing CMS Plugin');

        Process::fromShellCommandline('yarn add trix@^2.0.0 swiper@^11.2.6')->mustRun();
    }

    public function finalize(SymfonyStyle $io): void
    {
        $io->title('Finalizing CMS Plugin installation');

        $io->text(
            Process::fromShellCommandline('php bin/console doctrine:schema:update --force --complete')
                ->mustRun()
                ->getOutput()
        );

        $io->text(
            Process::fromShellCommandline('php bin/console sylius:fixtures:load cms -n')
                ->mustRun()
                ->getOutput()

        );
    }
}
