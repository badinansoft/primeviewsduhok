<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaAPISendMessageAction
{
    private string $token;
    private string $instanceId;
    private string $baseUrl;

    private array $headers;

    public function __construct()
    {
        $this->token = config('services.wa_api.api_token');
        $this->instanceId = config('services.wa_api.api_instance_id');
        $this->baseUrl = config('services.wa_api.api_url');
        $this->headers = ['Authorization' => 'Bearer ' . $this->token];
    }

    public function sendMessage(string $to, string $message): bool
    {
        $url = $this->baseUrl . '/instances/' . $this->instanceId . '/client/action/send-message';
        $data = ['chatId' => $to, 'message' => $message];

        $response = Http::withHeaders($this->headers)->post($url, $data);

        if ($response->failed()) {
            Log::error($response->body());
            return false;
        }

        if ($response->json()['data']['status'] !== 'success') {
            Log::error($response->body());
            return false;
        }

        return true;
    }

    public function sendPdfFileAsMessage(string $to, string $pdfFileUrl, string $caption): bool
    {
        $url = $this->baseUrl . '/instances/' . $this->instanceId . '/client/action/send-media';

        $data = [
            'chatId' => $to,
            'mediaUrl' => $pdfFileUrl,
            'mediaCaption' => $caption,
        ];


        $response = Http::withHeaders($this->headers)->post($url, $data);

        if ($response->failed()) {
            Log::error($response->body());
            return false;
        }

        if ($response->json()['data']['status'] !== 'success') {
            Log::error($response->body());
            return false;
        }

        return true;
    }
}
