<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KvkApiService
{
    public function getByKvkNumber(string $kvkNumber): array
    {
        return $this->request($this->buildPath('v1/basisprofielen/' . urlencode($kvkNumber)));
    }

    public function search(array $query = []): array
    {
        return $this->request($this->buildPath('v1/zoeken'), $query);
    }

    private function request(string $path, array $query = []): array
    {
        $baseUrl = rtrim((string) config('services.kvk.base_url'), '/');
        $apiKey = (string) config('services.kvk.key');
        $keyHeader = (string) config('services.kvk.key_header', 'apikey');
        $timeout = (int) config('services.kvk.timeout', 10);

        if ($apiKey === '') {
            throw new RuntimeException('KVK API key ontbreekt. Zet KVK_API_KEY in .env.');
        }

        /** @var Response $response */
        $response = Http::timeout($timeout)
            ->acceptJson()
            ->withHeaders([$keyHeader => $apiKey])
            ->get($baseUrl . $path, $query);

        $response->throw();

        return $response->json() ?? [];
    }

    private function buildPath(string $path): string
    {
        $baseUrl = rtrim((string) config('services.kvk.base_url'), '/');
        $cleanPath = ltrim($path, '/');

        if (str_ends_with($baseUrl, '/api')) {
            return '/'.$cleanPath;
        }

        return '/api/'.$cleanPath;
    }
}
