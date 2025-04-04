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

namespace spec\Sylius\CmsPlugin\MediaProvider;

use PhpSpec\ObjectBehavior;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\GenericProvider;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Uploader\MediaUploaderInterface;
use Twig\Environment;

final class GenericProviderSpec extends ObjectBehavior
{
    public function let(
        MediaUploaderInterface $uploader,
        Environment $twigEngine,
    ) {
        $this->beConstructedWith($uploader, $twigEngine, '@Template', '/media/');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(GenericProvider::class);
    }

    public function it_implements_provider_interface(): void
    {
        $this->shouldHaveType(ProviderInterface::class);
    }

    public function it_renders(MediaInterface $media, Environment $twigEngine): void
    {
        $twigEngine->render('@Template', ['media' => $media, 'config' => []])->willReturn('content');

        $this->render($media, '@Template', ['config' => []])->shouldReturn('content');
    }

    public function it_uploads(MediaInterface $media, MediaUploaderInterface $uploader): void
    {
        $uploader->upload($media, '/media/')->shouldNotBeCalled();
    }
}
