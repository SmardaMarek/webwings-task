<?php
class AuthenticaService
{
    private string $clientId;
    private string $clientSecret;
    private string $apiBaseUrl;
    private string $accessToken;

    public function __construct()
    {
        $this->clientId     = '07506fc47db879f25a41d55f87793aef';
        $this->clientSecret = 'aa216ee1aef389244f1f3aa4de32076b842dd95fe3463cf2e78183ef5cd89c189f4ee04fd4f721245f0cc506aa11285841d10e3f54f37deac47c5df324d34bd0';
        $this->apiBaseUrl   = 'https://delta.authentica.webwings.dev/api';
        $this->accessToken  = $this->getAccessToken();
    }

    private function getAccessToken(): string
    {
        $response = $this->post("{$this->apiBaseUrl}/token", [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ], false);

        return $response['access_token'] ?? throw new RuntimeException('Access token not received.');
    }

    public function createProduct(array $data): string
    {
        $response = $this->post("{$this->apiBaseUrl}/shop/11/product", $data);
        return $response['id'] ?? throw new RuntimeException('Product ID not returned.');
    }

    public function createOrder(array $data): string
    {
        $response = $this->post("{$this->apiBaseUrl}/shop/11/order", $data);
        return $response['id'] ?? throw new RuntimeException('Order ID not returned.');
    }

    public function uploadOrderInvoice(string $orderId, array $data): string
    {
        $response = $this->post("{$this->apiBaseUrl}/shop/11/order/{$orderId}/invoice", $data);
        return $response['id'] ?? throw new RuntimeException('Invoice ID not returned.');
    }

    private function post(string $url, array $data, bool $json = true): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($json) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new RuntimeException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        $decoded = json_decode($response, true);
        if (!$decoded) {
            throw new RuntimeException('Invalid JSON response.');
        }

        return $decoded;
    }
}
