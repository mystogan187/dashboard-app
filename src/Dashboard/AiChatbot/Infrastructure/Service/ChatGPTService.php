<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Infrastructure\Service;

use App\Dashboard\AiChatbot\Domain\Service\ChatServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ChatGPTService implements ChatServiceInterface
{
    private const API_URL = 'https://api.openai.com/v1/chat/completions';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $apiKey
    ) {}

    public function sendMessage(string $message): StreamedResponse
    {
        return new StreamedResponse(function() use ($message) {
            $response = $this->client->request('POST', self::API_URL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'text/event-stream',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $message],
                    ],
                    'max_tokens' => 500,
                    'stream' => true,
                ],
                'buffer' => false
            ]);

            foreach ($this->client->stream($response) as $chunk) {
                $content = $chunk->getContent();
                if (empty($content)) continue;

                $lines = explode("\n", $content);
                foreach ($lines as $line) {
                    if (str_starts_with($line, 'data: ')) {
                        $data = substr($line, 6);
                        if ($data === '[DONE]') continue;

                        $decoded = json_decode($data, true);
                        if (isset($decoded['choices'][0]['delta']['content'])) {
                            echo "data: " . json_encode([
                                    'content' => $decoded['choices'][0]['delta']['content']
                                ]) . "\n\n";
                            ob_flush();
                            flush();
                        }
                    }
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}