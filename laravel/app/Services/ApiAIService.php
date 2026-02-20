<?php

namespace App\Services;

use App\DTO\AiDTO;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ApiAIService
{
    public function models($url)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get($url);
            if ($response->successful()) {
                $models = [];
                $data = $response->json()["data"];
                foreach ($data as $item) {
                    $models[] = $item["id"];
                }
                return $models;
            }
            throw new \Exception('Ошибка получения моделей: ' . $response->body());
        } catch (RequestException $e) {
            throw new \Exception('Ошибка соединения с API: ' . $e->getMessage());
        }
    }

    public function chat(AiDTO $aiDTO, $url) : string
    {
        try {
            $payload = [
                'model' => $aiDTO->modelName,
                'messages' => [
                    ['role' => 'user', 'content' => $aiDTO->content]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ];
            $response = Http::withOptions([
                'verify' => false
            ])->post($url, $payload);
            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Нет ответа от модели';
            }
            throw new \Exception('Ошибка отправки сообщения: ' . $response->body());
        } catch (RequestException $e) {
            throw new \Exception('Ошибка соединения с API: ' . $e->getMessage());
        }
    }
}
