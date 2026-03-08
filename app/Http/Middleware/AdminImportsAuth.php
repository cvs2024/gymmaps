<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminImportsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedUser = (string) env('ADMIN_IMPORTS_USER');
        $expectedPass = (string) env('ADMIN_IMPORTS_PASSWORD');

        if ($expectedUser === '' || $expectedPass === '') {
            abort(500, 'ADMIN_IMPORTS_USER of ADMIN_IMPORTS_PASSWORD ontbreekt in .env');
        }

        $providedUser = (string) $request->getUser();
        $providedPass = (string) $request->getPassword();

        $valid = hash_equals($expectedUser, $providedUser)
            && hash_equals($expectedPass, $providedPass);

        if (!$valid) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED)
                ->header('WWW-Authenticate', 'Basic realm="Gymmap Admin Imports"');
        }

        return $next($request);
    }
}
