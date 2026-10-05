<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

interface Middleware
{
    /**
     * Jalankan penanganan middleware.
     * Jika mengembalikan objek Response, maka eksekusi route dihentikan dan response langsung dikirim.
     */
    public function handle(Request $request, ?string $param = null): ?Response;
}
