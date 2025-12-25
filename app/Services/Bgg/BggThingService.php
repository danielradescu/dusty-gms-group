<?php

namespace App\Services\Bgg;

class BggThingService
{
    public function __construct(protected BggApiClient $client) {}

    public function getDetails(array $bggIds): array
    {
        $chunks = array_chunk($bggIds, 20);
        $details = [];

        foreach ($chunks as $chunk) {
            $xml = $this->client->get('thing', [
                'id' => implode(',', $chunk),
            ]);

            foreach ($xml->item as $item) {
                $details[(int) $item['id']] = [
                    'is_expansion' => (string) $item['type'] === 'boardgameexpansion',
                ];
            }
        }

        return $details;
    }
}
