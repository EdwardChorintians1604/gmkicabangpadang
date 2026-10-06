<?php

declare(strict_types=1);

namespace App\Core;

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/core/Model.php
 * Deskripsi: Base Data Model (Ekuivalen SQLAlchemy Declarative Base)
 * =====================================================================
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    public static function getTable(): string
    {
        if (static::$table === '') {
            $class = (new \ReflectionClass(static::class))->getShortName();
            return strtolower($class) . 's';
        }
        return static::$table;
    }

    public static function query(): QueryBuilder
    {
        return QueryBuilder::table(static::getTable());
    }

    public static function find(int|string $id): ?array
    {
        return static::query()
            ->where(static::$primaryKey, '=', $id)
            ->first();
    }

    public static function where(string $column, string $operator, mixed $value): QueryBuilder
    {
        return static::query()->where($column, $operator, $value);
    }

    public static function all(): array
    {
        return static::query()->get();
    }

    public static function count(): int
    {
        return static::query()->count();
    }

    public static function create(array $data): int
    {
        return static::query()->insert($data);
    }

    public static function updateById(int|string $id, array $data): int
    {
        return static::query()
            ->where(static::$primaryKey, '=', $id)
            ->update($data);
    }

    public static function deleteById(int|string $id): int
    {
        return static::query()
            ->where(static::$primaryKey, '=', $id)
            ->delete();
    }
}
