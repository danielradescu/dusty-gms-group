<?php

namespace App\Services\Bgg;

class BggCollectionService
{
    public function __construct(protected BggApiClient $client) {}

    public function getUserCollection(string $username): array
    {
        $xml = $this->client->get('collection', [
            'username' => $username,
            'own' => 1,
            'stats' => 1,
        ]);

        $items = [];

        foreach ($xml->item as $item) {
            $bggId = (int) $item['objectid'];
            $stats = $item->stats ?? null;
            $rating = $stats?->rating ?? null;

            $boardgameRank = null;
            foreach ($rating?->ranks?->rank ?? [] as $rank) {
                if ((string)$rank['name'] === 'boardgame') {
                    $boardgameRank = (string)$rank['value'];
                }
            }

            $items[] = [
                'bgg_id' => $bggId,
                'name' => (string) $item->name,
                'year_published' => (int) ($item->yearpublished ?? 0),
                'min_players' => (int) ($stats['minplayers'] ?? 0),
                'max_players' => (int) ($stats['maxplayers'] ?? 0),
                'thumbnail' => (string) ($item->thumbnail ?? null),
                'image' => (string) ($item->image ?? null),
                'rank_boardgame' => $boardgameRank,
            ];
        }

        return $items;
    }
}
