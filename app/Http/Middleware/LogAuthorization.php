<?php
// app/Http/Middleware/LogAuthorization.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogAuthorization
{
    public function handle($request, Closure $next, ...$abilities)
    {
        $user = $request->user();
        
        if ($user && !empty($abilities)) {
            foreach ($abilities as $ability) {
                Log::info('🔍 Authorization Check', [
                    'timestamp' => now()->toDateTimeString(),
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_role' => $user->role ? $user->role->name : 'No role',
                    'ability' => $ability,
                    'hasPermission('.$ability.')' => $user->hasPermission($ability) ? 'YES' : 'NO',
                    'can('.$ability.')' => $user->can($ability) ? 'YES' : 'NO',
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                ]);
            }
        }
        
        return $next($request);
    }
}