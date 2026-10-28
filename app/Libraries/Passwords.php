<?php

namespace App\Libraries;

/**
 * Passwords of admins (admin.pass), parents (parents.passwd) and teachers (teachers.passwd).
 *
 * They are stored as password_hash() hashes (production's were hashed in place, see matches()). A password still in
 * plain text is checked the old way and replaced by a hash right after a successful sign-in, so nobody notices.
 * A hash is only written once the column is wide enough for it (app/Database/password_hashing.sql); until then the
 * plain password stays, so nobody is locked out by a cut-off hash.
 */
class Passwords
{
    // a new password (sign-ins with an older, weaker one still work): at least MIN_LENGTH characters, with an upper
    // and a lower case letter, a number and a symbol
    public const MIN_LENGTH = 8;
    public const RULES      = 'At least 8 characters, with an uppercase letter, a lowercase letter, a number and a symbol.';

    /**
     * What is wrong with $plain as a new password, or null if it is strong enough.
     */
    public static function problem(string $plain): ?string
    {
        $missing = [];
        if (! preg_match('/\p{Lu}/u', $plain)) {
            $missing[] = 'an uppercase letter';
        }
        if (! preg_match('/\p{Ll}/u', $plain)) {
            $missing[] = 'a lowercase letter';
        }
        if (! preg_match('/\d/', $plain)) {
            $missing[] = 'a number';
        }
        if (! preg_match('/[^\p{L}\d]/u', $plain)) {
            $missing[] = 'a symbol (such as ! @ # $ %)';
        }

        if (mb_strlen($plain) < self::MIN_LENGTH) {
            return 'The password needs at least ' . self::MIN_LENGTH . ' characters. ' . self::RULES;
        }
        if ($missing !== []) {
            return 'The password needs ' . implode(', ', $missing) . '. ' . self::RULES;
        }

        return null;
    }

    // sign-in attempts allowed per account and visitor, and per visitor, in THROTTLE_SECONDS
    private const PER_ACCOUNT      = 5;
    private const PER_VISITOR      = 20;
    private const THROTTLE_SECONDS = 600;

    /**
     * Whether $plain is the password stored as $stored. $legacy is the form the plain-text password was stored in
     * (parents: ' written as [$!)). Passwords hashed in place on the server are hashes of that stored form, so a
     * hash is checked against both; upgrade() then stores the hash of the password itself.
     */
    public static function matches(string $plain, ?string $stored, ?string $legacy = null): bool
    {
        $stored = (string) $stored;
        if ($plain === '' || $stored === '') {
            return false;
        }
        if (self::isHash($stored)) {
            return password_verify($plain, $stored)
                || ($legacy !== null && $legacy !== $plain && password_verify($legacy, $stored));
        }

        return hash_equals($stored, $legacy ?? $plain);
    }

    public static function isHash(?string $stored): bool
    {
        return password_get_info((string) $stored)['algo'] !== null;
    }

    /**
     * What to store for a new password in $table.$column: its hash, or the plain (legacy) form while the column is
     * too narrow for a hash.
     */
    public static function forStorage(string $table, string $column, string $plain, ?string $legacy = null): string
    {
        if (self::columnFits($table, $column)) {
            return password_hash($plain, PASSWORD_DEFAULT);
        }

        return $legacy ?? $plain;
    }

    /**
     * After a successful sign-in: replaces a plain-text (or outdated) password with a hash.
     */
    public static function upgrade(string $table, string $idColumn, $id, string $column, string $plain, ?string $stored): void
    {
        if (self::isHash($stored) && password_verify($plain, (string) $stored)
            && ! password_needs_rehash((string) $stored, PASSWORD_DEFAULT)) {
            return;
        }
        if (! self::columnFits($table, $column)) {
            return;
        }

        db_connect()->table($table)
            ->where($idColumn, $id)
            ->update([$column => password_hash($plain, PASSWORD_DEFAULT)]);
    }

    // whether $table.$column can hold a password_hash() hash (bcrypt: 60 characters; the SQL widens them to 255)
    public static function columnFits(string $table, string $column): bool
    {
        static $fits = [];
        $key = $table . '.' . $column;
        if (! isset($fits[$key])) {
            $width = 0;
            foreach (db_connect()->getFieldData($table) as $field) {
                if ($field->name === $column) {
                    $width = (int) $field->max_length;
                }
            }
            $fits[$key] = $width >= 60;
            if (! $fits[$key]) {
                log_message('warning', 'xilin: ' . $key . ' is ' . $width . ' characters wide, so passwords stay plain text; run app/Database/password_hashing.sql');
            }
        }

        return $fits[$key];
    }

    /**
     * Counts a sign-in (or password reset) attempt for $account from this visitor; false once there were too many.
     */
    public static function attemptAllowed(string $kind, string $account): bool
    {
        $throttler = service('throttler');
        $ip        = (string) service('request')->getIPAddress();

        // both checks always run, so every attempt counts against both limits
        $visitor = $throttler->check('login_' . md5($kind . '|' . $ip), self::PER_VISITOR, self::THROTTLE_SECONDS);
        $pair    = $throttler->check('login_' . md5($kind . '|' . $ip . '|' . strtolower(trim($account))), self::PER_ACCOUNT, self::THROTTLE_SECONDS);

        if (! $visitor || ! $pair) {
            log_message('notice', 'xilin: too many ' . $kind . ' attempts from ' . $ip);

            return false;
        }

        return true;
    }

    // parents' plain-text passwords were stored with ' written as [$!)
    public static function parentLegacy(string $plain): string
    {
        return str_replace("'", '[$!)', $plain);
    }
}
