<?php

namespace App\Core;

class Hash
{
    public static function make(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public static function check(string $password, string $hash): bool
    {
        if (password_verify($password, $hash)) {
            return true;
        }

        // Allow one login with legacy SHA-256 hashes so successful authentication can upgrade them to bcrypt.
        if (hash_equals(hash('sha256', $password), $hash)) {
            return true;
        }

        return false;
    }

    public static function needsRehash(string $hash): bool
    {
        if (!str_starts_with($hash, '$2y$')) {
            return true;
        }
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 10]);
    }
}
