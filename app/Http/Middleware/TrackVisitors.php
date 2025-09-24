<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorAnalytics;
use Carbon\Carbon;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes and API routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        $response = $next($request);

        // Track visitor after response is ready
        $this->trackVisitor($request);

        return $response;
    }

    private function trackVisitor(Request $request)
    {
        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $pageUrl = $request->fullUrl();
            $referrer = $request->header('referer');
            $visitDate = now()->toDateString();
            $visitTime = now()->toTimeString();

            // Check if this is a unique visitor (same IP, same day)
            $isUniqueVisitor = !VisitorAnalytics::where('ip_address', $ip)
                ->where('visit_date', $visitDate)
                ->exists();

            VisitorAnalytics::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'page_url' => $pageUrl,
                'referrer' => $referrer,
                'country' => null, // Could be enhanced with GeoIP
                'city' => null,   // Could be enhanced with GeoIP
                'visit_date' => $visitDate,
                'visit_time' => $visitTime,
                'session_duration' => 0, // Could be enhanced with session tracking
                'is_unique_visitor' => $isUniqueVisitor
            ]);
        } catch (\Exception $e) {
            // Silently fail to not break the application
            \Log::error('Visitor tracking failed: ' . $e->getMessage());
        }
    }
}
