<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Site;
use App\Support\Timezone;

class DomainValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->route('domain');
        $site = $this->domainInSitesTableWithPermission($domain, $request->user());
        if (!$site) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Domain not found'], 404);
            }
            return redirect()->route('manage-sites')->with('error', 'Domain not found');
        }
        $request->merge([
            'site_id' => $site->id,
            'site_timezone' => $site->timezone,
            'site_timezone_offset' => Timezone::getTimezoneOffset($site->timezone)
        ]);
        return $next($request);
    }

    protected function domainInSitesTableWithPermission($domain, $user)
    {
        $site = Site::where('fqdn', 'like', '%' . $domain . '%')->first();
        if ($site && $user->sites->contains($site)) {
            return $site;
        }
        return null;
    }
}
