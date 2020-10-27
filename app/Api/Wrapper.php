<?php

namespace App\Api;

use GuzzleHttp;

class Wrapper {

    const MAX_ATTEMPTS = 5;
    const BASE_URI = 'http://hiring.rewardgateway.net/';

    private $username = 'hard';
    private $password = 'hard';

    private $client;

    public function __construct() {

        $credentials = base64_encode($this->username . ":" . $this->password);

        $client = new GuzzleHttp\Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => 'Basic ' . $credentials,
            ]
        ]);

        $this->client = $client;
    }

    public function call($method, $path, $data = null) {
        $method = strtolower($method);
        $response = null;
        $attempts = 0;

        while (true) {
            try {
                if ($method == 'get') {
                    if ($data) {
                        $response = $this->client->request('get', $path, [
                            'query' => $data,
                        ]);
                    } else {
                        $response = $this->client->request('get', $path);
                    }
                } else {
                    if ($data) {
                        $response = $this->client->request($method, $path, [
                            'json' => $data,
                        ]);
                    } else {
                        $response = $this->client->request($method, $path);
                    }
                }

                $content = (string)$response->getBody();

                $responseData = json_decode($content);
                if (json_last_error()) {
                    throw new \Exception('Invalid response ' . json_encode([
                            'body' => $content,
                        ]));
                }
                if (gettype($responseData) == 'object' && property_exists($responseData, 'statuscode')) {
                    if ($responseData->statuscode != 200) {
                        if ($responseData->statuscode == 429) {
                            $attempts++;

                            if ($attempts > self::MAX_ATTEMPTS) {
                                throw new \Exception('Maximum number of attempts reached while trying to get a response.');
                            }

                            sleep(2);
                            continue;
                        }
                        throw new \Exception('Invalid response: ' . json_encode([
                                'responseData' => $responseData,
                            ]));
                    }
                }
                return $responseData;

            } catch (RequestException $ex) {
                if ($ex->getCode() == 429) {
                    $attempts++;

                    if ($attempts > self::MAX_ATTEMPTS) {
                        throw new \Exception('Maximum number of attempts reached while trying to get a response');
                    }

                    sleep(2);
                    continue;
                }

                \Log::error('Request exception while processing request', [
                    'ex' => $ex,
                ]);
                throw $ex;
            } catch (\Exception $ex) {
                \Log::error('Generic exception while processing request', [
                    'ex' => $ex,
                ]);
                throw $ex;
            }
        }
    }

}
