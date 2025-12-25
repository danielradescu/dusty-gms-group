<?php

namespace App\Services\Bgg;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;


class BggApiClient
{
    protected string $baseUrl = 'https://boardgamegeek.com/xmlapi2/';
    protected int $rateLimitDelay = 5; // seconds
    protected ?string $token;

    public function __construct()
    {

        $this->token = config('services.bgg.token'); // optional token support

        if (empty($this->token)) {
            Log::warning('[BGG] No API token configured. Proceeding with unauthenticated requests.');
        }
    }

    /**
     * Perform a GET request with retry for 202 responses.
     */
    public function get(string $endpoint, array $params = []): SimpleXMLElement
    {
        $url = $this->baseUrl . $endpoint;
        $attempts = 0;
        $delay = $this->rateLimitDelay;

        do {
            $attempts++;

            Log::info("ATTEMPT BGG request $url: ", $params);

            $response = Http::withHeaders($this->headers())->get($url, $params);



            if ($response->status() === 202) {
                Log::info("BGG 202 Accepted — waiting {$delay}s (attempt {$attempts})...");
                sleep($delay);
                $delay = min($delay * 2, 60); // exponential backoff
                continue;
            }

            // Handle generic HTTP failure
            if ($response->failed()) {
                Log::error("BGG API error: {$response->status()} - {$response->body()}");
                throw new \RuntimeException("BGG API request failed ({$response->status()})");
            }

            // Parse XML
            $xml = @simplexml_load_string($response->body());

            if (!$xml) {
                throw new \RuntimeException("Invalid XML returned from BGG API.");
            }

            // Detect <errors> node (BGG-style logical errors)
            if (isset($xml->error) || isset($xml->errors)) {
                $message = $this->extractErrorMessage($xml);
                Log::warning("BGG API returned logical error: {$message}");
                throw new \RuntimeException($message);
            }

            sleep($this->rateLimitDelay); // throttle next request
            return simplexml_load_string($response->body());

        } while ($response->status() === 202);

        throw new \Exception("BGG request never completed after multiple retries.");
    }

    protected function headers(): array
    {
        return $this->token
            ? ['Authorization' => 'Bearer ' . $this->token]
            : [];
    }

    /**
     * Extracts readable error message from a BGG <errors> XML.
     */
    protected function extractErrorMessage(SimpleXMLElement $xml): string
    {
        if (isset($xml->error->message)) {
            return trim((string) $xml->error->message);
        }

        if (isset($xml->errors->error->message)) {
            return trim((string) $xml->errors->error->message);
        }

        return 'An unknown error occurred while communicating with BoardGameGeek.';
    }
}
