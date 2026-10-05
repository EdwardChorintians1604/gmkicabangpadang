<?php

namespace App\Middleware;

use App\Core\Auth as CoreAuth;
use App\Core\Request;
use App\Core\Response;

class Guest implements Middleware
{
    public function handle(Request $request, ?string $param = null): ?Response
    {
        if (CoreAuth::check()) {
            $response = new Response();
            $role = CoreAuth::role();
            if ($role === 'pengawas') {
                $response->redirect('/pengawas/dashboard');
            } else {
                $response->redirect('/admin/dashboard');
            }
            return $response;
        }

        return null;
    }
}
