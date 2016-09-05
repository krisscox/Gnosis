<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;
use App\Models\Gnosis\Permission;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class Can
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permission = Permission::whereName($permission)->with('roles')->first();
        
        if (!$permission || Auth::user()->hasPermission($permission)) {
            return $next($request);
        }

        throw new AuthenticationException;
    }
}
