<?php

namespace App\Core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    protected function validate(): void
    {
        foreach ($this->rules as $field => $ruleList) {
            $rules = is_array($ruleList) ? $ruleList : explode('|', $ruleList);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$ruleName, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $this->applyRule($field, $value, $ruleName, $params);
            }
        }
    }

    protected function applyRule(string $field, mixed $value, string $rule, array $params): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "Kolom {$this->formatField($field)} wajib diisi.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Format {$this->formatField($field)} tidak valid.");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "Kolom {$this->formatField($field)} harus berupa angka.");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) < $min) {
                    $this->addError($field, "Kolom {$this->formatField($field)} minimal {$min} karakter.");
                } elseif (is_numeric($value) && $value < $min) {
                    $this->addError($field, "Nilai {$this->formatField($field)} minimal {$min}.");
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) > $max) {
                    $this->addError($field, "Kolom {$this->formatField($field)} maksimal {$max} karakter.");
                } elseif (is_numeric($value) && $value > $max) {
                    $this->addError($field, "Nilai {$this->formatField($field)} maksimal {$max}.");
                }
                break;

            case 'in':
                if (!empty($value) && !in_array($value, $params, true)) {
                    $this->addError($field, "Pilihan pada {$this->formatField($field)} tidak valid.");
                }
                break;

            case 'confirmed':
                $confirmationField = $field . '_confirmation';
                $confirmationValue = $this->data[$confirmationField] ?? null;
                if ($value !== $confirmationValue) {
                    $this->addError($field, "Konfirmasi {$this->formatField($field)} tidak cocok.");
                }
                break;

            case 'unique':
                // unique:table,column,except_id
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;

                if (!empty($value) && $table && $column) {
                    $sql = "SELECT COUNT(*) as cnt FROM `{$table}` WHERE `{$column}` = :val";
                    $bindings = [':val' => $value];

                    if ($exceptId !== null) {
                        $sql .= " AND id != :except_id";
                        $bindings[':except_id'] = $exceptId;
                    }

                    $row = Database::fetchOne($sql, $bindings);
                    if ($row && $row['cnt'] > 0) {
                        $this->addError($field, "{$this->formatField($field)} sudah terdaftar di sistem.");
                    }
                }
                break;
        }
    }

    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    protected function formatField(string $field): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $field));
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(?string $field = null): ?string
    {
        if ($field !== null) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $fieldErrors) {
            if (!empty($fieldErrors)) {
                return $fieldErrors[0];
            }
        }
        return null;
    }
}
