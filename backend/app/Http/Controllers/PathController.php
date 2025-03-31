<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Endpoint;

class PathController extends BaseController
{
    public function handle($path = '', Request $request)
    {
        $method = $request->getMethod();

        // Cherche un endpoint actif
        $endpoint = Endpoint::where('path', '/'.$path)
            ->where('method', $method)
            ->where('is_active', true)
            ->first();
        $logId = $request->attributes->get('log_id', null);

        if (!$endpoint) {
            if ($logId) {
                \App\Models\Log::where('id', $logId)->update(['matched' => false]);
            }
            return response('Not found', 404);
        }

        // Récupérer l'ID du log
        if ($logId) {
            // Si on a un endpoint, matched = true, sinon false
            \App\Models\Log::where('id', $logId)->update(['matched' => true]);
        }


        // 1) Logique paramétrée 
        //    Si l'user veut faire correspondre certains paramètres => renvoyer un "match" vs "default"
        $paramsConfigured = json_decode($endpoint->params_json, true) ?? [];
        $responseMap = json_decode($endpoint->response_json, true) ?? []; 
        // Si on a un "match" ou "default" => on applique
        if (!empty($responseMap)) {
            foreach ($paramsConfigured as $param => $expectedValue) {
                $actualValue = $request->query($param, null);
                if ($actualValue === $expectedValue && isset($responseMap['match'])) {
                    return $this->buildCustomResponse($endpoint, $responseMap['match']);
                }
            }
            if (isset($responseMap['default'])) {
                return $this->buildCustomResponse($endpoint, $responseMap['default']);
            }
        }

        // 2) Sinon, si on a un "response_body", on le renvoie tel quel
        if (!empty($endpoint->response_body)) {
            return $this->buildCustomResponse($endpoint, $endpoint->response_body);
        }

        // 3) Sinon => message par défaut
        return response('Endpoint matched but no response defined', 200);
    }

    /**
     * Construit la réponse HTTP (status code, reason phrase, headers).
     * $content peut être du texte, JSON, binaire (base64), etc.
     */
    private function buildCustomResponse(Endpoint $endpoint, $content)
    {
        // On crée une réponse brute
        // Dans Lumen, response() peut prendre un body direct
        // On n'impose pas le JSON => c'est du contenu brut
        $code = $endpoint->status_code ?: 200;
        $message = $endpoint->status_message ?: null;

        $response = response($content, $code);
        if ($message) {
            // Lumen permet setStatusCode($code, $message), 
            // mais le "message" est souvent ignoré selon l'environnement
            $response->setStatusCode($code, $message);
        }

        // Ajout des headers
        if ($endpoint->headers_json) {
            $headers = json_decode($endpoint->headers_json, true);
            if (is_array($headers)) {
                foreach ($headers as $hKey => $hVal) {
                    $response->header($hKey, $hVal);
                }
            }
        }

        // Si l'utilisateur n'a pas défini de Content-Type,
        // Lumen risque de mettre 'text/html' par défaut.
        // A toi de décider si tu mets un fallback 'text/plain'
        // ou tu laisses tel quel.
        return $response;
    }
}
