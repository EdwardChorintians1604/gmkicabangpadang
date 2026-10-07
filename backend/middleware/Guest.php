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
            if (in_array($role, ['ketcab', 'pengawas'], true)) {
                $response->redirect('/ketcab/dashboard');
            } elseif (in_array($role, ['sekcab', 'bencab', 'sekfung_medko', 'operator'], true)) {
                $response->redirect('/ruang-kerja');
            } else {
                $response->redirect('/admin/dashboard');
            }
            return $response;
        }

        return null;
    }
}
