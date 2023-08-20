<?php


namespace ReleaseProtection\Http\Middleware;

use Closure;

class VerifyFirstPartyClientIp
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $type
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $type = 'default')
    {
        $allowIPs = config('release-protection.ip.ips.' . $type, config('release-protection.ip.ips.default'));
        if (
            app()->environment(
                config('release-protection.ip.envs.' . $type, config('release-protection.ip.envs.default'))
            ) &&
            !in_array($request->ip(), $allowIPs)
        ) {
            abort(
                config('release-protection.ip.status.restricted.' . $type, config('release-protection.ip.status.restricted.default')),
                __(config('release-protection.ip.msg.restricted.' . $type, config('release-protection.ip.msg.restricted.default')))
            );
        }

        return $next($request);
    }
}
