<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boardgame extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'bgg_id',
        'name',
        'year_published',
        'min_players',
        'max_players',
        'thumbnail',
        'image',
        'rank_boardgame',
        'is_expansion',
    ];

    /**
     * Casts for specific attributes.
     */
    protected $casts = [
        'is_expansion' => 'boolean',
        'year_published' => 'integer',
        'min_players' => 'integer',
        'max_players' => 'integer',
    ];

    /**
     * Relationships
     */

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Accessors / Helpers
     */

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ?: $this->thumbnail;
    }

    public function getIsRankedAttribute(): bool
    {
        return is_numeric($this->rank_boardgame);
    }

    public function scopeExpansions($query)
    {
        return $query->where('is_expansion', true);
    }

    public function scopeBaseGames($query)
    {
        return $query->where('is_expansion', false);
    }

    public function getBggUrlAttribute(): string
    {
        $slug = str($this->name)
            ->ascii()              // convert special chars to ASCII
            ->lower()              // lowercase
            ->slug('-');           // replace spaces and symbols with dashes

        return "https://boardgamegeek.com/boardgame/{$this->bgg_id}/{$slug}";
    }

    /**
     * Automatically normalize the rank_boardgame value.
     */
    public function setRankBoardgameAttribute($value): void
    {
        // If it's numeric, store as string (so "45" not 45)
        if (is_numeric($value)) {
            $this->attributes['rank_boardgame'] = (string) $value;
            return;
        }

        // If it's empty, "Not Ranked", or anything invalid → store null
        $value = trim((string) $value);
        if ($value === '' || strcasecmp($value, 'Not Ranked') === 0) {
            $this->attributes['rank_boardgame'] = null;
            return;
        }

        // Fallback: ensure null for anything unexpected
        $this->attributes['rank_boardgame'] = null;
    }

    public function getRankBoardgameAttribute($value): ?string
    {
        return $value ?? 'Not Ranked';
    }

    public function scopeOrderedByRank($query)
    {
        return $query->orderByRaw("
        CASE
            WHEN rank_boardgame REGEXP '^[0-9]+$' THEN 0
            ELSE 1
        END ASC,
        CAST(rank_boardgame AS UNSIGNED) ASC
    ");
    }

    public static function sanitizeForUpsert(array $rows): array
    {
        return collect($rows)->map(function ($item) {
            // --- rank_boardgame ---
            $rank = trim((string)($item['rank_boardgame'] ?? ''));
            $item['rank_boardgame'] = is_numeric($rank) ? (int)$rank : null;

            // --- year_published ---
            $yearRaw = trim((string)($item['year_published'] ?? ''));

            // Remove non-digits entirely (so "?" or "N/A" become "")
            $year = preg_replace('/\D/', '', $yearRaw);

            if ($year === '' || !is_numeric($year)) {
                $year = null;
            } else {
                $year = (int)$year;
                // BoardgameGeek can’t have real years outside 1000–(next year)
                if ($year < 1000 || $year > (int)date('Y') + 1) {
                    $year = null;
                }
            }

            $item['year_published'] = $year;

            // --- player counts ---
            foreach (['min_players', 'max_players'] as $key) {
                $val = trim((string)($item[$key] ?? ''));
                $item[$key] = (is_numeric($val) && (int)$val > 0 && (int)$val <= 99)
                    ? (int)$val
                    : null;
            }

            // --- ensure valid URLs ---
            foreach (['image', 'thumbnail'] as $key) {
                $val = trim((string)($item[$key] ?? ''));
                $item[$key] = str_starts_with($val, 'http') ? $val : null;
            }

            return $item;
        })->toArray();
    }

}
