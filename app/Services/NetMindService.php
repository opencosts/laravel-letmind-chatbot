<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NetMindService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl = 'https://api.netmind.ai/inference-api/openai/v1'; // Check actual API endpoint

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('NETMIND_API_KEY');
    }

    public function chatCompletion(array $messages, string $model = 'Qwen/Qwen2.5-VL-72B-Instruct', bool $stream = false)
    {
        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    'max_tokens' => 512,
                ],
            ];

            if ($stream) {
                $options['stream'] = true;
                $options['json']['stream'] = true;
            }

            $response = $this->client->post("{$this->baseUrl}/chat/completions", $options);

            return $stream ? $response->getBody() : json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
