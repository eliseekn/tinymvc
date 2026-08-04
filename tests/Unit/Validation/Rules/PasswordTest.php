<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Validation\Rules;

use App\Http\Validation\Rules\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function test_passes_for_valid_password(): void
    {
        $this->assertTrue((new Password)->check('P@ssw0rd'));
    }

    public function test_fails_without_uppercase_letter(): void
    {
        $this->assertFalse((new Password)->check('p@ssw0rd'));
    }

    public function test_fails_without_lowercase_letter(): void
    {
        $this->assertFalse((new Password)->check('P@SSW0RD'));
    }

    public function test_fails_without_digit(): void
    {
        $this->assertFalse((new Password)->check('P@ssword'));
    }

    public function test_fails_without_special_character(): void
    {
        $this->assertFalse((new Password)->check('Passw0rd'));
    }

    public function test_fails_for_empty_value(): void
    {
        $this->assertFalse((new Password)->check(''));
    }
}
