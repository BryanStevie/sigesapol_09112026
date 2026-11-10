<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class API_Saludpol {
  private $Urlsldp;
  private $usersldp;
  private $passwordsldp;
  private $client;

  public function __construct() {
    $this->Urlsldp  = config('app.api_saludpol_url');
    $this->usersldp     = config('app.api_saludpol_username');
    $this->passwordsldp = config('app.api_saludpol_pass');

    $this->client = new Client([
      'base_uri' => $this->Urlsldp . '/',
      'verify'   => false,
      'timeout'  => 10,
    ]);
  }

  private function getToken() {
    return Cache::remember('saludpol_api_token', 55, function () {
      $response = $this->client->post('auth/token', [ 'json' => [
          'username' => $this->usersldp,
          'password' => $this->passwordsldp,
        ]
      ]);

      $body = json_decode($response->getBody(), true);

      if (empty($body['token'])) {
          throw new \Exception('No se pudo obtener el token de SaludPol');
      }

      return $body['token'];
    });
  }

  public function get($endpoint, $params = []) {
    try {
        $response = $this->client->get($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ],
            'query' => $params,
        ]);

        return json_decode($response->getBody(), true);

    } catch (\GuzzleHttp\Exception\ClientException $e) {

      if ($e->getResponse()->getStatusCode() === 401) {
          Cache::forget('saludpol_api_token');

          $response = $this->client->get($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ],
            'query' => $params,
          ]);

          return json_decode($response->getBody(), true);
      }

      throw new \Exception("Error en SaludPol [{$endpoint}]: " . $e->getMessage());
    }
  }

  public function post($endpoint, $body = []) {
    try {
        $response = $this->client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Content-Type'  => 'application/json',
            ],
            'json' => $body,
        ]);

        return json_decode($response->getBody(), true);

    } catch (\GuzzleHttp\Exception\ClientException $e) {

        if ($e->getResponse()->getStatusCode() === 401) {
            Cache::forget('saludpol_api_token');

            $response = $this->client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            return json_decode($response->getBody(), true);
        }

        throw new \Exception("Error en SaludPol [{$endpoint}]: " . $e->getMessage());
    }
  }
}