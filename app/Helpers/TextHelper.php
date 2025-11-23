<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * Detect URLs in a string and replace them with smart, clickable links.
     */
    public static function linkify(?string $text): string
    {
        if (! $text) {
            return '';
        }

        // Regex to detect URLs (basic but safe)
        $pattern = '/\bhttps?:\/\/[^\s<]+/i';

        return preg_replace_callback($pattern, function ($matches) {
            $url = e($matches[0]);

            // Detect BoardGameGeek links
            if (preg_match('/boardgamegeek\.com\/boardgame\/\d+\/([\w\-]+)/i', $url, $bggMatch)) {
                $slug = $bggMatch[1] ?? '';
                $gameName = ucwords(str_replace('-', ' ', $slug));
                $label = $gameName;
            }
            // Detect Google Maps links
            elseif (str_contains($url, 'maps.app.goo.gl') || str_contains($url, 'google.com/maps')) {
                $label = 'View on map';
            }
            // Default fallback
            else {
                $label = 'Click here';
            }

            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer"
                       class="text-indigo-500 hover:text-indigo-400 underline">' . e($label) . '</a>';
        }, e($text));
    }
}
