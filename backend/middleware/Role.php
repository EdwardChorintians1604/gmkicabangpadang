<?php

namespace App\Middleware;

use App\Core\Auth as CoreAuth;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class Role implements Middleware
{
    public function handle(Request $request, ?string $param = null): ?Response
    {
        if (CoreAuth::guest()) {
            $response = new Response();
            $response->redirect('/login');
            return $response;
        }

        if ($param === null) {
            return null;
        }

        $allowedRoles = explode(',', $param);
        $userRole = CoreAuth::role();

        if (!in_array($userRole, $allowedRoles, true)) {
            http_response_code(403);
            return View::render('errors.403', ['message' => 'Anda tidak memiliki hak akses ke bagian ini.'], null)->setStatusCode(403);
        }

        return null;
    }
}
