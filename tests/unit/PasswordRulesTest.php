<?php

use App\Libraries\Passwords;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PasswordRulesTest extends CIUnitTestCase
{
    public function testStrongPasswordsPass(): void
    {
        $this->assertNull(Passwords::problem('Abcdef1!'));
        $this->assertNull(Passwords::problem('Xilin 2026 school'));
        $this->assertNull(Passwords::problem('Ünïcode9#'));
    }

    public function testTooShort(): void
    {
        $this->assertStringContainsString('too short', Passwords::problem('Ab1!xyz'));
    }

    public function testEachMissingKind(): void
    {
        $this->assertStringContainsString('an uppercase letter', Passwords::problem('abcdef1!'));
        $this->assertStringContainsString('a lowercase letter', Passwords::problem('ABCDEF1!'));
        $this->assertStringContainsString('a number', Passwords::problem('Abcdefg!'));
        $this->assertStringContainsString('a symbol', Passwords::problem('Abcdefg1'));
        $this->assertNotNull(Passwords::problem(''));
    }
}
