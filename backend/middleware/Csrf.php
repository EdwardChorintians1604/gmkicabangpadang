<?php

namespace App\Middleware;

use App\Core\Csrf as CoreCsrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class Csrf implements Middleware
{
    public function handle(Request $request, ?string $param = null): ?Response
    {
        // CSRF verification only applies to modifying requests
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            $token = $request->input('_csrf_token') ?? $request->input('_csrf');

            if (!$token) {
                // Periksa header HTTP
                $headers = getallheaders();
                $token = $headers['X-CSRF-TOKEN'] ?? $headers['x-csrf-token'] ?? null;
            }

            if (!CoreCsrf::validate($token)) {
                Session::flash('error', 'Sesi formulir telah kedaluwarsa atau token tidak valid. Silakan coba kembali.');
                $response = new Response();
                $response->redirect($_SERVER['HTTP_REFERER'] ?? '/');
                return $response;
            }
        }

        return null;
    }
}
