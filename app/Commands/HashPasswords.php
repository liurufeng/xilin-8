<?php

namespace App\Commands;

use App\Libraries\Passwords;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Hashes every parent and admin password that is still plain text, without waiting for each account's next sign-in.
 * Optional: sign-in hashes them anyway (Libraries/Passwords.php). Run on the server: php spark xilin:hash-passwords
 */
class HashPasswords extends BaseCommand
{
    protected $group       = 'Xilin';
    protected $name        = 'xilin:hash-passwords';
    protected $description = 'Replaces plain-text passwords (parents.passwd, admin.pass) with password_hash() hashes.';

    public function run(array $params)
    {
        $db = db_connect();

        // parents' plain passwords were stored with ' written as [$!); the hash is of the password itself
        $tables = [
            ['parents', 'parent_id', 'passwd', static fn (string $stored): string => str_replace('[$!)', "'", $stored)],
            ['admin', 'id', 'pass', static fn (string $stored): string => $stored],
        ];

        foreach ($tables as [$table, $idColumn, $column, $plain]) {
            if (! Passwords::columnFits($table, $column)) {
                CLI::error($table . '.' . $column . ' is too narrow for a hash; run app/Database/password_hashing.sql first.');

                return EXIT_ERROR;
            }

            $done = 0;
            foreach ($db->table($table)->select($idColumn . ', ' . $column)->get()->getResultArray() as $row) {
                $stored = (string) $row[$column];
                if ($stored === '' || Passwords::isHash($stored)) {
                    continue;
                }
                $db->table($table)->where($idColumn, $row[$idColumn])
                    ->update([$column => password_hash($plain($stored), PASSWORD_DEFAULT)]);
                $done++;
            }
            CLI::write($table . ': ' . $done . ' password(s) hashed.');
        }

        return EXIT_SUCCESS;
    }
}
