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

namespace Tests\Sylius\CmsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Sylius\CmsPlugin\Behat\Page\Shop\HomePageInterface;
use Webmozart\Assert\Assert;

final class HomepageBlocksContext implements Context
{
    public function __construct(private HomePageInterface $blockHomePage)
    {
    }

    /**
     * @When I go to the homepage
     */
    public function iGoToTheHomepage(): void
    {
        $this->blockHomePage->open();
    }

    /**
     * @Then I want to see a text block with :content content
     */
    public function iWantToSeeATextBlockWithContent(string $content): void
    {
        Assert::true($this->blockHomePage->hasBlockWithContent($content));
    }
}
