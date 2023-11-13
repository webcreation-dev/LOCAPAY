<?php

namespace App\Services;

use GuzzleHttp\Client;

class MtnMobileMoneyService
{
    protected $baseUrl;
    protected $subscriptionKey;
    protected $apiUserId;
    protected $apiKey;
    public function __construct()
    {
        $this->baseUrl = env('MTN_BASE_URL');
        $this->subscriptionKey = env('MTN_SUBSCRIPTION_KEY');
        $this->apiUserId = env('MTN_API_USER_ID');
        $this->apiKey = env('MTN_API_KEY');
    }

    /**
     * Creates an API user.
     *
     * @return array The JSON response from the API.
     */
    public function createApiUser()
    {
        $client = new Client();

        $headers = [
            'X-Reference-Id' => uniqid(),
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
        ];

        $json = [
            'providerCallbackHost' => 'clinic.com', // Adjust as needed
        ];

        $response = $client->post($this->baseUrl . '/apiuser', [
            'headers' => $headers,
            'json' => $json,
        ]);

        return $response->getBody()->getContents();
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

        $url = $this->baseUrl . "/apiuser/{$apiUserId}/apikey";
        $headers = ['Ocp-Apim-Subscription-Key' => $this->subscriptionKey];

        $response = $client->post($url, ['headers' => $headers]);

        return $response->getBody()->getContents();
    }

    public function requestToPay($amount, $currency, $payerMobileNumber)
    {
        // Step 1: Create API User
        $apiUserResponse = $this->createApiUser();
        $apiUserId = $apiUserResponse['id']; // Extract the API user ID from the response

        // Step 2: Create API Key
        $apiKeyResponse = $this->createApiKey($apiUserId);
        $apiKey = $apiKeyResponse['apiKey']; // Extract the API key from the response

        // Step 3: Request to Pay
        $client = new Client();

        $response = $client->post($this->baseUrl . '/collection/v1_0/requesttopay', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'X-Reference-Id' => uniqid(),
                'X-Target-Environment' => 'sandbox',
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
