<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/models/User.php
 * Deskripsi: Model Pengguna & Autentikasi (SQLAlchemy / Doctrine Style)
 * =====================================================================
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static string $primaryKey = 'id';

    /**
     * Cari akun berdasarkan username (kebal SQL Injection via Parameterized Query)
     */
    public static function findByUsername(string $username): ?array
    {
        return static::query()
            ->where('username', '=', $username)
            ->first();
    }

    /**
     * Cari akun berdasarkan email
     */
    public static function findByEmail(string $email): ?array
    {
        return static::query()
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * Cari akun untuk login (mencocokkan username ATAU email sekaligus)
     */
    public static function findForAuthentication(string $identifier): ?array
    {
        return static::query()
            ->where('username', '=', $identifier)
            ->orWhere('email', '=', $identifier)
            ->first();
    }
}
