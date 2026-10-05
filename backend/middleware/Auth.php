<?php

namespace App\Middleware;

use App\Core\Auth as CoreAuth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class Auth implements Middleware
{
    public function handle(Request $request, ?string $param = null): ?Response
    {
        if (CoreAuth::guest()) {
            Session::flash('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman tersebut.');
            $response = new Response();
            $response->redirect('/login');
            return $response;
        }

        return null;
    }
}
