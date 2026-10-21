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
    public const MIN_LENGTH = 6;

    // sign-in attempts allowed per account and visitor, and per visitor, in THROTTLE_SECONDS
    private const PER_ACCOUNT      = 5;
    private const PER_VISITOR      = 20;
    private const THROTTLE_SECONDS = 600;

    // how long a password reset link works
    public const RESET_SECONDS = 3600;

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

    /**
     * A new password reset link token for a parent or teacher (table password_resets). Only its sha256 is stored.
     */
    public static function newResetToken(string $userType, int $userId): ?string
    {
        $db = db_connect();
        if (! $db->tableExists('password_resets')) {
            log_message('error', 'xilin: table password_resets is missing; run app/Database/password_hashing.sql');

            return null;
        }

        $token = bin2hex(random_bytes(32));
        $db->table('password_resets')->where('expires_at <', date('Y-m-d H:i:s'))->delete();
        $db->table('password_resets')->insert([
            'user_type'  => $userType,
            'user_id'    => $userId,
            'token_hash' => hash('sha256', $token),
            'expires_at' => date('Y-m-d H:i:s', time() + self::RESET_SECONDS),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    /**
     * The unused, unexpired password_resets row of $token, or null.
     */
    public static function findResetToken(string $token): ?array
    {
        if (! preg_match('/\A[0-9a-f]{64}\z/', $token)) {
            return null;
        }

        $row = db_connect()->table('password_resets')
            ->where('token_hash', hash('sha256', $token))
            ->where('used_at', null)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get(1)
            ->getRowArray();

        return $row ?: null;
    }

    // a reset link works once: this one is used up, and any other open link of the same account stops working too
    public static function useResetToken(array $row): void
    {
        db_connect()->table('password_resets')
            ->where('user_type', $row['user_type'])
            ->where('user_id', $row['user_id'])
            ->where('used_at', null)
            ->update(['used_at' => date('Y-m-d H:i:s')]);
    }

    // parents' plain-text passwords were stored with ' written as [$!)
    public static function parentLegacy(string $plain): string
    {
        return str_replace("'", '[$!)', $plain);
    }
}
