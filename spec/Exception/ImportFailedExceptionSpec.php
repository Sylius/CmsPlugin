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

namespace spec\Sylius\CmsPlugin\Exception;

use PhpSpec\ObjectBehavior;
use Sylius\CmsPlugin\Exception\ImportFailedException;

final class ImportFailedExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('not blank', 1);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ImportFailedException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(\Exception::class);
    }

    public function it_has_custom_message(): void
    {
        $this->getMessage()->shouldReturn('Import failed at index 1. Exception message: not blank');
    }
}
