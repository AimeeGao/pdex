<?php

namespace App\Http\Middleware\Student;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Individual;
use Carbon\Carbon;

class CheckProfile
{
    /**
     * Handle an incoming request to check if student has a valid profile.
     * 
     * This middleware ensures that students:
     * 1. Have an individual profile record
     * 2. Have updated their profile within the last PROFILE_UPDATE_GRACE_PERIOD months (default 6 months)
     * 
     * If either condition is not met, they are redirected to create/update their profile.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Skip profile check for profile-related routes to avoid infinite redirects
        $profileRoutes = [
            'student.profile.index',
            'student.profile.create',
            'student.profile.store',
            'student.profile.edit',
            'student.profile.update'
        ];
        
        if (in_array($request->route()?->getName(), $profileRoutes)) {
            return $next($request);
        }

        // Check if user has an individual profile
        $individual = Individual::where('user_guid', $user->guid)->first();
        
        if (!$individual) {
            // No profile exists - redirect to create one
            Log::info('Student profile check: No profile found for user', [
                'user_guid' => $user->guid,
                'redirecting_to' => 'profile.create'
            ]);
            
            return redirect()->route('student.profile.create')
                ->with('info', 'Please create your profile to continue accessing the student dashboard.');
        }

        // Check if profile was updated within the last PROFILE_UPDATE_GRACE_PERIOD months
        $lastUpdated = $individual->updated_at;
        
        // Handle case where updated_at might be null (shouldn't happen with timestamps, but just in case)
        if (!$lastUpdated) {
            $lastUpdated = $individual->created_at;
        }

        $timeAgo = Carbon::now()->subMonths(env('PROFILE_UPDATE_GRACE_PERIOD', 6));

        if ($lastUpdated->lt($timeAgo)) {
            // Profile is outdated - redirect to update it
            Log::info('Student profile check: Profile outdated', [
                'user_guid' => $user->guid,
                'individual_guid' => $individual->guid,
                'last_updated' => $lastUpdated->toDateTimeString(),
                'time_ago' => $timeAgo->toDateTimeString(),
                'redirecting_to' => 'profile.edit'
            ]);
            
            return redirect()->route('student.profile.edit', ['guid' => $individual->guid])
                ->with('warning', 'Your profile information is more than ' . env('PROFILE_UPDATE_GRACE_PERIOD', 6) . ' months old. Please update your profile to continue.');
        }

        return $next($request);
    }
}
