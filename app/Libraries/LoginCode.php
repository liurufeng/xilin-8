<?php

namespace App\Libraries;

/**
 * 6-digit codes sent by email. Kinds:
 * - 'parent', 'admin': the second sign-in step; after the password, the code must be typed in before the sign-in
 *   finishes. Parents get the code at their own email; admins' codes go to ADMIN_EMAIL.
 * - 'reset': a parent's forgotten password (Controllers/Password.php); the code proves the email is theirs.
 *
 * Until then the user's data waits in the session (login_code_<kind>) with the code's hash, so the earlier step is
 * not repeated when the code is checked or sent again.
 */
class LoginCode
{
    public const ADMIN_EMAIL = 'ec@xilinnschinese.org';

    // how long a code works, how many wrong codes end the sign-in, and the wait before another code can be sent
    public const CODE_SECONDS   = 600;
    private const MAX_TRIES     = 5;
    private const RESEND_SECONDS = 60;

    /**
     * Starts $kind: keeps $user for finish() and emails a code to $to. False if the email could not be sent.
     * With no $user (a reset asked for an unregistered email) nothing is sent and no code matches, but the pages
     * answer the same, so they can't be used to find out who is registered.
     */
    public static function start(string $kind, array $user, string $to): bool
    {
        session()->set('login_code_' . $kind, [
            'user'  => $user,
            'to'    => $to,
            'hash'  => '',
            'until' => 0,
            'tries' => 0,
            'sent'  => 0,
        ]);

        return self::send($kind);
    }

    /**
     * Emails a new code for the pending sign-in of $kind (the old one stops working). False if there is none, it is
     * too soon after the last one, or the email could not be sent.
     */
    public static function send(string $kind): bool
    {
        $pending = self::pending($kind);
        if ($pending === null || time() - $pending['sent'] < self::RESEND_SECONDS) {
            return false;
        }

        $code = sprintf('%06d', random_int(0, 999999));
        $pending['hash']  = password_hash($code, PASSWORD_DEFAULT);
        $pending['until'] = time() + self::CODE_SECONDS;
        $pending['tries'] = 0;
        $pending['sent']  = time();
        if ($pending['user'] === []) {
            $pending['hash'] = '';
        }
        session()->set('login_code_' . $kind, $pending);
        if ($pending['user'] === []) {
            return true;
        }

        $host    = parse_url(base_url(), PHP_URL_HOST);
        $minutes = 'It works for ' . (self::CODE_SECONDS / 60) . ' minutes.';
        if ($kind === 'reset') {
            $subject = 'Your ' . $host . ' password reset code';
            $content = 'Your password reset code is: ' . $code . "\r\n\r\n" . $minutes
                . " If you didn't ask to reset your password, you can ignore this email; your password stays the same.";
        } else {
            $subject = 'Your ' . $host . ' sign-in code';
            $content = ($kind === 'admin' ? 'Admin sign-in for ' . ($pending['user']['name'] ?? '') . ".\r\n\r\n" : '')
                . 'Your sign-in code is: ' . $code . "\r\n\r\n" . $minutes
                . " If you didn't try to sign in, you can ignore this email; someone may know the password, so"
                . ' please change it.';
        }

        return self::mail($pending['to'], $subject, $content);
    }

    /**
     * Sends a plain-text email from ADMIN_EMAIL, also as the envelope sender (-f) like the invoice emails, so the
     * host's mail server and spam filters accept it. False (and a log line) if mail() refused it.
     */
    public static function mail(string $to, string $subject, string $content): bool
    {
        $to      = str_replace(["\r", "\n"], '', $to);
        $headers = "From: Xilin Northshore Chinese School <" . self::ADMIN_EMAIL . ">\r\n"
            . "Reply-To: " . self::ADMIN_EMAIL . "\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n";
        try {
            $sent = mail($to, $subject, $content, $headers, '-f ' . self::ADMIN_EMAIL);
        } catch (\ErrorException $e) {
            $sent = false;
            log_message('error', 'xilin: email "' . $subject . '" not sent: ' . $e->getMessage());
        }
        if (! $sent) {
            log_message('error', 'xilin: mail() did not accept the email "' . $subject . '"');
        }

        return $sent;
    }

    /**
     * Checks $code for the pending sign-in of $kind. Returns the user kept by start() and ends the pending sign-in
     * when it is right; null when it is wrong (after MAX_TRIES wrong codes or once it expired, the sign-in ends too).
     */
    public static function finish(string $kind, string $code): ?array
    {
        $pending = self::pending($kind);
        if ($pending === null) {
            return null;
        }
        if (time() > $pending['until'] || $pending['tries'] >= self::MAX_TRIES) {
            self::cancel($kind);

            return null;
        }

        $code = preg_replace('/\s+/', '', $code);
        if ($pending['hash'] !== '' && preg_match('/\A\d{6}\z/', $code) && password_verify($code, $pending['hash'])) {
            self::cancel($kind);

            return $pending['user'];
        }

        $pending['tries']++;
        if ($pending['tries'] >= self::MAX_TRIES) {
            self::cancel($kind);
        } else {
            session()->set('login_code_' . $kind, $pending);
        }

        return null;
    }

    // the pending sign-in of $kind, or null
    public static function pending(string $kind): ?array
    {
        $pending = session()->get('login_code_' . $kind);

        return is_array($pending) ? $pending : null;
    }

    public static function cancel(string $kind): void
    {
        session()->remove('login_code_' . $kind);
    }

    // the address shown on the code page: j***@example.com
    public static function maskedTo(string $kind): string
    {
        $to = (string) (self::pending($kind)['to'] ?? '');
        $at = strpos($to, '@');
        if ($at === false) {
            return '';
        }

        return substr($to, 0, 1) . str_repeat('*', max(1, $at - 1)) . substr($to, $at);
    }
}
