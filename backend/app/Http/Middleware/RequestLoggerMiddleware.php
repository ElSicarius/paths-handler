<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Récupère le chemin complet (avec slash initial éventuel)
        $path = $request->path(); // ex: "admin-xxxxx/endpoints"

        // Vérifie si le path commence par "admin-" ou équivalent.
        // On suppose que dans .env on a ADMIN_PATH=admin
        // Mais pour couvrir toutes les variantes, on peut checker "admin-" en dur
        // OU on peut faire un check plus dynamique si besoin.
        $adminPath = env('ADMIN_PATH', 'admin');

        if (!preg_match('#^'.$adminPath.'#', $path)) {
            // On log la requête brute
            // Pour l'avoir, on peut reconstituer manuellement l'équivalent HTTP
            $raw = sprintf(
                "%s %s %s\r\n",
                $request->getMethod(),
                $request->fullUrl(),
                $request->server('SERVER_PROTOCOL')
            );

            // Ajoute les headers
            foreach ($request->headers->all() as $header => $values) {
                foreach ($values as $value) {
                    $raw .= sprintf("%s: %s\r\n", $header, $value);
                }
            }

            // Séparateur avant le body
            $raw .= "\r\n";

            // Ajoute le body brut
            $raw .= $request->getContent();

            // Enregistre en base
            $log = Log::create([
                'raw_request' => $raw,
                // matched vaut false par défaut, via la migration
            ]);
            // On stocke l'ID en attribut de la requête
            $request->attributes->set('log_id', $log->id);
        }

        return $next($request);
    }
}
