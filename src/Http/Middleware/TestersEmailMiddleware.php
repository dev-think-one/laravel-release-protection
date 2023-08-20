<?php


namespace ReleaseProtection\Http\Middleware;

use Auth;
use Closure;

class TestersEmailMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (app()->environment(config('release-protection.email.envs.' . $guard, config('release-protection.email.envs.default')))) {
            $user = Auth::guard($guard)->user();
            if (!$user) {
                abort(
                    config('release-protection.email.status.not_logged.' . $guard, config('release-protection.email.status.not_logged.default')),
                    __(config('release-protection.email.msg.not_logged.' . $guard, config('release-protection.email.msg.not_logged.default')))
                );
            }

            $allowEmails = config('release-protection.email.emails.' . $guard, config('release-protection.email.emails.default'));
            $allowEmails = array_map(fn ($e) => trim(strtolower($e)), $allowEmails);

            if (!$user->email || !in_array(strtolower($user->email), $allowEmails)) {
                abort(
                    config('release-protection.email.status.restricted.' . $guard, config('release-protection.email.status.restricted.default')),
                    __(config('release-protection.email.msg.restricted.' . $guard, config('release-protection.email.msg.restricted.default')))
                );
            }
        }

        return $next($request);
    }
}
