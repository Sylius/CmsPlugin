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

    public function install(string $version, $input, $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Installing CMS Plugin');
        $process = Process::fromShellCommandline('cat package.json');
        $exitCode = $process->run();
        if ($exitCode === 0) {
            echo $process->getOutput();
        } else {
            echo "Błąd: ", $process->getErrorOutput();
        }




        Process::fromShellCommandline('php bin/console sylius:fixtures:load cms -n')->run();
        Process::fromShellCommandline('yarn add trix@^2.0.0 swiper@^11.2.6')->run();

        $io->success('Po dodaniu trixa');
        $process = Process::fromShellCommandline('cat package.json');
        $exitCode = $process->run();
        if ($exitCode === 0) {
            echo $process->getOutput();
        } else {
            echo "Błąd: ", $process->getErrorOutput();
        }

    }
}
