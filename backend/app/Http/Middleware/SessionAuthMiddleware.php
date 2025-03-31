<?php

namespace App\Http\Middleware;

use Closure;

class SessionAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Si pas connecté
        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // On redirige explicitement vers /admin-xxx/login
            $adminPath = env('ADMIN_PATH', 'admin');
            // On peut construire l'URL absolue :
            $url = $request->getSchemeAndHttpHost().'/'.$adminPath.'/login';
            // Ou bien juste "/$adminPath/login" si on veut rester en chemin relatif
            return redirect($url);
        }

        return $next($request);
    }
}
