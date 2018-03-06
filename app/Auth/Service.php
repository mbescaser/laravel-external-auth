<?php

namespace App\Auth;

use App\Constants;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Service {

    private $http;

    public function __construct() {
        $this->http = new Client([
            'base_uri' => Constants::SERVER_API
        ]);
    }

    public function loginUser($credentials) {
        $response = null;
        try {
            $response = $this->http->request('POST', "users/login", [
                'headers' => [
        			'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
        		],
                'auth' => [$credentials['email'], $credentials['password']]
            ]);
        } catch(RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            } else {
                $response = null;
            }
        }
        if($response && in_array($response->getStatusCode(), [200, 201])) {
            return json_decode($response->getBody());
        }
        return null;
    }

    public function getUser($auth) {
        $response = null;
        try {
            $response = $this->http->request('GET', "users/{$auth->userId}", [
                'headers' => [
        			'Accept' => 'application/json',
                    'Authorization' => "Bearer {$auth->accessToken}"
        		]
            ]);
        } catch(RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            } else {
                $response = null;
            }
        }
        if($response && in_array($response->getStatusCode(), [200, 201])) {
            return json_decode($response->getBody());
        }
        return null;
    }
}
