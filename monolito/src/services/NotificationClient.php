<?php

class NotificationClient {
    // URL del servicio (definida por Docker Compose)
    private $baseUrl = 'http://notifications:8080';

    public function sendEmail($to, $orderId) {
        $url = $this->baseUrl . '/send-email';

        // Datos según el contrato
        $data = [
            'To' => $to,
            'OrderId' => (int)$orderId,
            'Subject' => 'Confirmación de Compra - Ecommerce',
        ];

        $payload = json_encode($data);

        // Configuración de cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            // Lanzamos error para que lo maneje el index.php
            throw new Exception("No se pudo contactar al servicio de notificaciones: $error");
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("El servicio respondió con error HTTP: $httpCode");
        }

        return json_decode($result, true);
    }

    public function sendEmailAsync($to, $orderId) {
        $url = $this->baseUrl . '/send-email-async';

        // Datos según el contrato
        $data = [
            'To' => $to,
            'OrderId' => (int)$orderId,
            'Subject' => 'Confirmación de Compra - Ecommerce',
        ];

        $payload = json_encode($data);

        // Configuración de cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout de 2 segundos para no bloquear al usuario eternamente
        //Si no responde en ese tiempo, se asume que el servicio no está disponible y se maneja el error
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            // Lanzamos error para que lo maneje el index.php
            throw new Exception("No se pudo contactar al servicio de notificaciones: $error");
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("El servicio respondió con error HTTP: $httpCode");
        }

        return json_decode($result, true);
    }
}
?>