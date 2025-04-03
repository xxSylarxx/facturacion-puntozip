<?php

namespace api;

class ApiGuiasPuntozip
{
    const BASE_URL_API = 'http://89.117.145.178:8081/api/v1/sistema-guias/';

    public function listarGuiasPorEmitir(string $empresa)
    {
        $url = self::BASE_URL_API . "$empresa";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('Error en cURL: ' . curl_error($ch));
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            error_log("Error HTTP: $httpCode, respuesta: $response");
            return null;
        }
        curl_close($ch);
        $data = json_decode($response, true);
        return $data;
    }

    public function actualizarEstadoGuia() {}
}
