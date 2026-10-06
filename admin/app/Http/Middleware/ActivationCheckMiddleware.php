<?php

namespace App\Http\Middleware;

use App\Traits\ActivationClass;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

class ActivationCheckMiddleware
{
    use ActivationClass;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $area = null): mixed
    {
        $response = $this->checkActivationCache(app: $area);
        if (!$response) {
            if (!strpos(url()->current(), '/api/v1')) {
                return Redirect::away(route(base64_decode('c3lzdGVtLmFjdGl2YXRpb24tY2hlY2s=')))->send();
            }

            $locale = strtolower((string) $request->header('X-localization', config('app.locale')));
            $allowed = ['es', 'en', 'ar', 'bn'];
            if (!in_array($locale, $allowed, true)) {
                $locale = 'en';
            }
            App::setLocale($locale);

            $messageKey = match ($area) {
                'restaurant_app' => 'messages.activation_check_restaurant_app',
                'deliveryman_app' => 'messages.activation_check_deliveryman_app',
                'react_web' => 'messages.activation_check_react_web',
                'admin_panel' => 'messages.activation_check_admin_panel',
                default => 'messages.activation_check_generic',
            };
            $message = $messageKey === 'messages.activation_check_generic'
                ? __($messageKey, ['area' => str_replace('_', ' ', (string) $area)])
                : __($messageKey);

            return response()->json([
                'code' => 503,
                'message' => $message,
            ], 503);
        }
        return $next($request);
    }
}
