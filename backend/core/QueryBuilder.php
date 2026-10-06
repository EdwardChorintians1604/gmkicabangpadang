<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use InvalidArgumentException;

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/core/QueryBuilder.php
 * Deskripsi: Query Expression Builder & ORM Core (Ekuivalen SQLAlchemy / Doctrine DBAL di PHP)
 * Fitur Keamanan:
 * - 100% Parameterized Prepared Statements (Mencegah SQL Injection)
 * - Strict Identifier Whitelisting (Validasi nama tabel & kolom)
 * - Typed Binding via PDO Binary Protocol
 * =====================================================================
 */
class QueryBuilder
{
    protected string $table = '';
    protected array $columns = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $orders = [];
    protected ?int $limitValue = null;
    protected ?int $offsetValue = null;
    protected int $paramCounter = 0;

    public function __construct(string $table = '')
    {
        if ($table !== '') {
            $this->from($table);
        }
    }

    public static function table(string $table): self
    {
        return new self($table);
    }

    public function from(string $table): self
    {
        $this->validateIdentifier($table);
        $this->table = $table;
        return $this;
    }

    public function select(string ...$columns): self
    {
        if (!empty($columns)) {
            $this->columns = [];
            foreach ($columns as $col) {
                if ($col !== '*') {
                    $this->validateIdentifier($col);
                }
                $this->columns[] = $col;
            }
        }
        return $this;
    }

    /**
     * Tambahkan klausa WHERE dengan prepared parameter
     */
    public function where(string $column, string $operator, mixed $value): self
    {
        return $this->addWhere('AND', $column, $operator, $value);
    }

    /**
     * Tambahkan klausa OR WHERE
     */
    public function orWhere(string $column, string $operator, mixed $value): self
    {
        return $this->addWhere('OR', $column, $operator, $value);
    }

    /**
     * Klausa WHERE IN
     */
    public function whereIn(string $column, array $values): self
    {
        $this->validateIdentifier($column);
        if (empty($values)) {
            // WHERE 0 = 1 jika array kosong
            $this->wheres[] = ['boolean' => 'AND', 'sql' => '0 = 1'];
            return $this;
        }

        $placeholders = [];
        foreach ($values as $val) {
            $param = ':qb_in_' . (++$this->paramCounter);
            $placeholders[] = $param;
            $this->bindings[$param] = $val;
        }

        $escapedCol = $this->escapeIdentifier($column);
        $sql = "{$escapedCol} IN (" . implode(', ', $placeholders) . ")";
        $this->wheres[] = ['boolean' => 'AND', 'sql' => $sql];

        return $this;
    }

    /**
     * Klausa WHERE NULL / NOT NULL
     */
    public function whereNull(string $column): self
    {
        $this->validateIdentifier($column);
        $escapedCol = $this->escapeIdentifier($column);
        $this->wheres[] = ['boolean' => 'AND', 'sql' => "{$escapedCol} IS NULL"];
        return $this;
    }

    public function whereNotNull(string $column): self
    {
        $this->validateIdentifier($column);
        $escapedCol = $this->escapeIdentifier($column);
        $this->wheres[] = ['boolean' => 'AND', 'sql' => "{$escapedCol} IS NOT NULL"];
        return $this;
    }

    /**
     * Order By
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->validateIdentifier($column);
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orders[] = $this->escapeIdentifier($column) . ' ' . $direction;
        return $this;
    }

    /**
     * Limit & Offset
     */
    public function limit(int $limit, ?int $offset = null): self
    {
        $this->limitValue = max(0, $limit);
        if ($offset !== null) {
            $this->offsetValue = max(0, $offset);
        }
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offsetValue = max(0, $offset);
        return $this;
    }

    /**
     * Ambil 1 baris pertama
     */
    public function first(): ?array
    {
        $clone = clone $this;
        $clone->limit(1);
        $sql = $clone->toSelectSql();
        return Database::fetchOne($sql, $clone->bindings);
    }

    /**
     * Ambil seluruh baris hasil
     */
    public function get(): array
    {
        $sql = $this->toSelectSql();
        return Database::fetchAll($sql, $this->bindings);
    }

    /**
     * Hitung jumlah baris (COUNT)
     */
    public function count(): int
    {
        $clone = clone $this;
        $clone->columns = ['COUNT(*) as aggregate_total'];
        $sql = $clone->toSelectSql();
        $row = Database::fetchOne($sql, $clone->bindings);
        return (int)($row['aggregate_total'] ?? 0);
    }

    /**
     * Periksa keberadaan baris
     */
    public function exists(): bool
    {
        return $this->first() !== null;
    }

    /**
     * Insert data dengan prepared statement
     */
    public function insert(array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException("Data insert tidak boleh kosong.");
        }

        $cols = [];
        $placeholders = [];
        $params = [];

        foreach ($data as $col => $val) {
            $this->validateIdentifier((string)$col);
            $param = ':qb_ins_' . (++$this->paramCounter);
            $cols[] = $this->escapeIdentifier((string)$col);
            $placeholders[] = $param;
            $params[$param] = $val;
        }

        $tableEscaped = $this->escapeIdentifier($this->table);
        $sql = "INSERT INTO {$tableEscaped} (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $placeholders) . ")";

        Database::execute($sql, $params);
        return (int)Database::lastInsertId();
    }

    /**
     * Update data dengan prepared statement
     */
    public function update(array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException("Data update tidak boleh kosong.");
        }

        $setPairs = [];
        $params = [];

        foreach ($data as $col => $val) {
            $this->validateIdentifier((string)$col);
            $param = ':qb_upd_' . (++$this->paramCounter);
            $setPairs[] = $this->escapeIdentifier((string)$col) . " = {$param}";
            $params[$param] = $val;
        }

        $tableEscaped = $this->escapeIdentifier($this->table);
        $sql = "UPDATE {$tableEscaped} SET " . implode(', ', $setPairs);

        [$whereSql, $whereBindings] = $this->buildWhereSql();
        if ($whereSql !== '') {
            $sql .= ' ' . $whereSql;
            $params = array_merge($params, $whereBindings);
        }

        $stmt = Database::query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Delete data
     */
    public function delete(): int
    {
        $tableEscaped = $this->escapeIdentifier($this->table);
        $sql = "DELETE FROM {$tableEscaped}";

        [$whereSql, $whereBindings] = $this->buildWhereSql();
        if ($whereSql !== '') {
            $sql .= ' ' . $whereSql;
        }

        $stmt = Database::query($sql, $whereBindings);
        return $stmt->rowCount();
    }

    /**
     * Bangun SQL SELECT lengkap
     */
    public function toSelectSql(): string
    {
        $cols = array_map(function ($c) {
            return $c === '*' ? '*' : $this->escapeIdentifier($c);
        }, $this->columns);

        $tableEscaped = $this->escapeIdentifier($this->table);
        $sql = "SELECT " . implode(', ', $cols) . " FROM {$tableEscaped}";

        [$whereSql] = $this->buildWhereSql();
        if ($whereSql !== '') {
            $sql .= ' ' . $whereSql;
        }

        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limitValue !== null) {
            $sql .= ' LIMIT ' . $this->limitValue;
            if ($this->offsetValue !== null) {
                $sql .= ' OFFSET ' . $this->offsetValue;
            }
        }

        return $sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    protected function addWhere(string $boolean, string $column, string $operator, mixed $value): self
    {
        $this->validateIdentifier($column);
        $operator = $this->normalizeOperator($operator);

        $param = ':qb_p_' . (++$this->paramCounter);
        $escapedCol = $this->escapeIdentifier($column);
        $sql = "{$escapedCol} {$operator} {$param}";

        $this->wheres[] = [
            'boolean' => strtoupper($boolean) === 'OR' ? 'OR' : 'AND',
            'sql' => $sql,
        ];
        $this->bindings[$param] = $value;

        return $this;
    }

    protected function buildWhereSql(): array
    {
        if (empty($this->wheres)) {
            return ['', []];
        }

        $parts = [];
        foreach ($this->wheres as $i => $w) {
            if ($i === 0) {
                $parts[] = $w['sql'];
            } else {
                $parts[] = $w['boolean'] . ' ' . $w['sql'];
            }
        }

        return ['WHERE ' . implode(' ', $parts), $this->bindings];
    }

    protected function validateIdentifier(string $identifier): void
    {
        // Izinkan nama kolom atau tabel alfanumerik dan underscore (serta titik untuk alias)
        if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $identifier)) {
            throw new InvalidArgumentException("Nama identifier database tidak valid/mencurigakan: '{$identifier}'");
        }
    }

    protected function escapeIdentifier(string $identifier): string
    {
        if (str_contains($identifier, '.')) {
            $parts = explode('.', $identifier);
            return implode('.', array_map(fn($p) => "`" . str_replace("`", "``", $p) . "`", $parts));
        }
        return "`" . str_replace("`", "``", $identifier) . "`";
    }

    protected function normalizeOperator(string $operator): string
    {
        $allowed = ['=', '!=', '<>', '<', '<=', '>', '>=', 'LIKE', 'NOT LIKE'];
        $clean = strtoupper(trim($operator));
        if (!in_array($clean, $allowed, true)) {
            throw new InvalidArgumentException("Operator SQL tidak diizinkan: '{$operator}'");
        }
        return $clean;
    }
}
