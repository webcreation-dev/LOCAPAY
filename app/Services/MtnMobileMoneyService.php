<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class MtnMobileMoneyService
{
    protected $baseUrl;
    protected $subscriptionKey;
    protected $apiKey;
    protected $xref;
    public function __construct()
    {
        $this->baseUrl = env('MTN_BASE_URL');
        $this->subscriptionKey = env('MTN_SUBSCRIPTION_KEY');
        $this->xref = $this->generateUuidV4();
    }
    /**
     * Generates a version 4 universally unique identifier (UUID).
     *
     * @return string The generated UUID.
     */
    public  function generateUuidV4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Creates an API user.
     *
     * @throws Exception If there is an error creating the API user.
     *
     * @return string The ID of the created API user.
     */
    public function createApiUser()
    {
        $client = new Client();
        $headers = [
            'X-Reference-Id' => $this->xref,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
        ];

        $json = [
            'providerCallbackHost' => 'clinic.com',
        ];

        $options = [
            'headers' => $headers,
            'json' => $json,
        ];

        $response = $client->post($this->baseUrl . 'v1_0/apiuser', $options);

        if ($response->getStatusCode() === 201) {
            return 'USER CREATED';
        } else {
            throw new Exception('Failed to create API user');
        }
    }


    /**
     * Creates an API key for a given API user.
     *
     * @param int $apiUserId The ID of the API user.
     *
     * @throws Exception If there is an error creating the API key.
     *
     * @return array The JSON response containing the created API key.
     */
    public function createApiKey($apiUserId)
    {
        $client = new Client();

        $url = $this->baseUrl . "v1_0/apiuser/{$apiUserId}/apikey";
        $headers = ['Ocp-Apim-Subscription-Key' => $this->subscriptionKey];

        $response = $client->post($url, ['headers' => $headers]);

        return $response->getBody()->getContents();
    }
    public function CreateAccessToken($apiUserId, $apiKey)
    {
        $client = new Client();
        $url = $this->baseUrl . "collection/token";
        $headers = [
            'Authorization' => 'Basic ' . base64_encode($apiUserId . ':' . $apiKey),
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
        ];

        $response = $client->post($url, ['headers' => $headers]);
        $body = $response->getBody()->getContents();
        dd($body);
    }

    public function requestToPay($amount, $currency, $payerMobileNumber)
    {
        // Step 1: Create API User
        $apiUserId = $this->createApiUser();

        // Step 2: Create API Key
        $apiKeyResponse = $this->createApiKey($this->xref);
        $apiKey = json_decode($apiKeyResponse, true)['apiKey'];

        // Step 3: Générate Access Token
        $accessToken = $this->CreateAccessToken($this->xref, $apiKey);

        // Step 3: Request to Pay
        $client = new Client();

        $response = $client->post($this->baseUrl . '/collection/v1_0/requesttopay', [
            'headers' => [
                'Authorization' => $accessToken,
                'X-Reference-Id' => $this->xref,
                'X-Target-Environment' => 'sandbox',
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            ],
            'json' => [
                'amount' => $amount,
                'currency' => $currency,
                'externalId' => uniqid(),
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $payerMobileNumber,
                ],
                'payerMessage' => 'Payment for Product',
                'payeeNote' => 'Thank you for your purchase',
            ],


        ]);

        return $response->getBody()->getContents();
    }
}
