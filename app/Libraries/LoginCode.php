<?php

namespace App\Libraries;

/**
 * The second sign-in step: after the password, a 6-digit code is emailed and must be typed in before the sign-in
 * finishes. Parents get the code at their own email; admins' codes go to ADMIN_EMAIL.
 *
 * Until then the signed-in user's data waits in the session (login_code_<kind>) with the code's hash, so the
 * password step is not repeated when the code is checked or sent again.
 */
class LoginCode
{
    public const ADMIN_EMAIL = 'ec@xilinnschinese.org';

    // how long a code works, how many wrong codes end the sign-in, and the wait before another code can be sent
    public const CODE_SECONDS   = 600;
    private const MAX_TRIES     = 5;
    private const RESEND_SECONDS = 60;

    /**
     * Starts the second step for $kind ('parent' or 'admin'): keeps $user for finish() and emails a code to $to.
     * False if the email could not be sent.
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
        session()->set('login_code_' . $kind, $pending);

        $host    = parse_url(base_url(), PHP_URL_HOST);
        $to      = str_replace(["\r", "\n"], '', $pending['to']);
        $subject = 'Your ' . $host . ' sign-in code';
        $content = ($kind === 'admin' ? 'Admin sign-in for ' . ($pending['user']['name'] ?? '') . ".\r\n\r\n" : '')
            . 'Your sign-in code is: ' . $code . "\r\n\r\nIt works for " . (self::CODE_SECONDS / 60) . ' minutes.'
            . " If you didn't try to sign in, you can ignore this email; someone may know the password, so"
            . ' please change it.';
        $headers = "From: Xilin Northshore Chinese School <" . self::ADMIN_EMAIL . ">\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n";
        try {
            return mail($to, $subject, $content, $headers, '-f ' . self::ADMIN_EMAIL);
        } catch (\ErrorException $e) {
            log_message('error', 'xilin: sign-in code email not sent: ' . $e->getMessage());

            return false;
        }
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
        if (preg_match('/\A\d{6}\z/', $code) && password_verify($code, $pending['hash'])) {
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
