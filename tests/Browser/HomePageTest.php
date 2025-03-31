<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser;

use Core\Testing\BrowserTestCase;

class HomePageTest extends BrowserTestCase
{
    public function test_home_page_title_is_correct(): void
    {
        $this
            ->visit('/')
            ->assertPageTitleSame('TinyMVC | PHP framework based on MVC architecture')
            ->assertSelectorTextContains('h1', 'TinyMVC')
            ->assertSelectorTextContains('a[href="https://github.com/eliseekn/tinymvc"]', 'Github');
    }
}
