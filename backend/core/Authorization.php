<?php

namespace App\Core;

class Authorization
{
    protected static ?array $rolesConfig = null;

    protected static function loadConfig(): array
    {
        if (self::$rolesConfig === null) {
            self::$rolesConfig = require dirname(__DIR__) . '/config/roles.php';
        }
        return self::$rolesConfig;
    }

    public static function can(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $role = Auth::role();
        if (!$role) {
            return false;
        }

        // Administrator memiliki akses penuh ke segala hak
        if ($role === 'admin') {
            return true;
        }

        $config = self::loadConfig();
        $permissions = $config['permissions'][$role] ?? [];

        return in_array($permission, $permissions, true);
    }

    public static function role(string|array $allowedRoles): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $currentRole = Auth::role();
        if (is_array($allowedRoles)) {
            return in_array($currentRole, $allowedRoles, true);
        }

        return $currentRole === $allowedRoles;
    }

    public static function authorize(string $permission): void
    {
        if (!self::can($permission)) {
            http_response_code(403);
            require_once dirname(__DIR__, 2) . '/frontend/templates/errors/403.php';
            exit;
        }
    }

    public static function authorizeRole(string|array $allowedRoles): void
    {
        if (!self::role($allowedRoles)) {
            http_response_code(403);
            require_once dirname(__DIR__, 2) . '/frontend/templates/errors/403.php';
            exit;
        }
    }
}
