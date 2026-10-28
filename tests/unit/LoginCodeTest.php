<?php

use App\Libraries\LoginCode;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class LoginCodeTest extends CIUnitTestCase
{
    // a pending sign-in with a known code, as send() would leave it (send() itself emails)
    private function pending(string $code, int $until = 0): void
    {
        session()->set('login_code_parent', [
            'user'  => ['parent_id' => 7],
            'to'    => 'jane@example.com',
            'hash'  => password_hash($code, PASSWORD_DEFAULT),
            'until' => $until ?: time() + 600,
            'tries' => 0,
            'sent'  => time(),
        ]);
    }

    public function testRightCodeFinishesOnce(): void
    {
        $this->pending('123456');
        $this->assertSame(['parent_id' => 7], LoginCode::finish('parent', ' 123 456 '));
        $this->assertNull(LoginCode::pending('parent'));
        $this->assertNull(LoginCode::finish('parent', '123456'));
    }

    public function testWrongCodesEndTheSignIn(): void
    {
        $this->pending('123456');
        for ($i = 0; $i < 4; $i++) {
            $this->assertNull(LoginCode::finish('parent', '000000'));
            $this->assertNotNull(LoginCode::pending('parent'));
        }
        $this->assertNull(LoginCode::finish('parent', '000000'));
        $this->assertNull(LoginCode::pending('parent'));
    }

    public function testExpiredCode(): void
    {
        $this->pending('123456', time() - 1);
        $this->assertNull(LoginCode::finish('parent', '123456'));
        $this->assertNull(LoginCode::pending('parent'));
    }

    public function testResendWaits(): void
    {
        $this->pending('123456');
        $this->assertFalse(LoginCode::send('parent'));
        $this->assertSame('j***@example.com', LoginCode::maskedTo('parent'));
    }

    // a reset asked for an unregistered email: no email, and no code matches
    public function testResetWithoutAccountMatchesNoCode(): void
    {
        $this->assertTrue(LoginCode::start('reset', [], 'nobody@example.com'));
        $this->assertSame('', LoginCode::pending('reset')['hash']);
        $this->assertNull(LoginCode::finish('reset', '000000'));
        $this->assertNull(LoginCode::finish('reset', ''));
        $this->assertNotNull(LoginCode::pending('reset'));
    }
}
