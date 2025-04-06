<?php

namespace api;

use function GuzzleHttp\json_encode;

class ApiGuiasPuntozip
{
    const BASE_URL_API = 'http://89.117.145.178:8081/api/v1/sistema-guias';

    public function listarGuiasPorEmitir(string $empresa)
    {
        $url = self::BASE_URL_API . "/$empresa";
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
        return $response;
    }

    public function obtenerGuia($id)
    {
        $url = self::BASE_URL_API . "/guias/$id";
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
        return json_decode($response, true);
    }

    public function actualizarEstadoGuia($data)
    {
        try {
            $serieCorrelativo = $data['serie'] . '-' . $data['correlativo'];
            $idGuiaIntegracion = $data['id_guia_integracion'];
            $url = self::BASE_URL_API . "/actualizacion/$idGuiaIntegracion";
            $dataUpdate = json_encode([
                'serie_correlativo' => $serieCorrelativo
            ]);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataUpdate);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataUpdate)
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode != 200) {
                error_log("Error en la actualización de la guía de integración: " . $response);
            }
        } catch (\Exception $e) {
            error_log("Error en la actualización de la guía de integración: " . $e->getMessage());
        }
    }
}
