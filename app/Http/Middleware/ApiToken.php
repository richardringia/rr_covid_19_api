<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiToken as ApiTokenModel;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');

        if ($header != null) {
            if (ApiTokenModel::where('key', $header)->first() != null) {
                return $next($request);
            }
        }
        return response()->json('Unauthorized', 401);
    }
}
