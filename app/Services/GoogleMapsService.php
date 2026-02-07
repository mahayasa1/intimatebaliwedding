<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleMapsService
{
    protected $apiKey;
    protected $placeId;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key');
        $this->placeId = config('services.google_maps.place_id');
    }

    /**
     * Fetch reviews from Google Maps Place API
     * 
     * @param int $limit Number of reviews to fetch
     * @return array
     */
    public function getReviews($limit = 6)
    {
        // Cache reviews for 1 hour to avoid hitting API limits
        $cacheKey = "google_maps_reviews_{$this->placeId}_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $this->placeId,
                    'fields' => 'name,rating,reviews,user_ratings_total,url',
                    'key' => $this->apiKey,
                    'language' => 'en',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['result']['reviews'])) {
                        $reviews = collect($data['result']['reviews'])
                            ->take($limit)
                            ->map(function ($review) {
                                return [
                                    'author_name' => $review['author_name'] ?? 'Anonymous',
                                    'author_photo' => $review['profile_photo_url'] ?? null,
                                    'rating' => $review['rating'] ?? 5,
                                    'text' => $review['text'] ?? '',
                                    'time' => $review['time'] ?? time(),
                                    'relative_time' => $review['relative_time_description'] ?? 'Recently',
                                    'language' => $review['language'] ?? 'en',
                                ];
                            })
                            ->toArray();

                        return [
                            'success' => true,
                            'reviews' => $reviews,
                            'total_ratings' => $data['result']['user_ratings_total'] ?? 0,
                            'average_rating' => $data['result']['rating'] ?? 0,
                            'place_url' => $data['result']['url'] ?? $this->getPlaceUrl(),
                        ];
                    }
                }

                Log::warning('Google Maps API returned no reviews', [
                    'place_id' => $this->placeId,
                    'response' => $response->json()
                ]);

                return $this->getFallbackReviews();

            } catch (\Exception $e) {
                Log::error('Error fetching Google Maps reviews', [
                    'error' => $e->getMessage(),
                    'place_id' => $this->placeId,
                ]);

                return $this->getFallbackReviews();
            }
        });
    }

    /**
     * Get direct Google Maps URL for the place
     * 
     * @return string
     */
    public function getPlaceUrl()
    {
        return "https://search.google.com/local/writereview?placeid={$this->placeId}";
    }

    /**
     * Get Google Maps business page URL
     * 
     * @return string
     */
    public function getBusinessUrl()
    {
        return "https://www.google.com/maps/place/?q=place_id:{$this->placeId}";
    }

    /**
     * Clear reviews cache manually
     * 
     * @return bool
     */
    public function clearCache()
    {
        $cacheKey = "google_maps_reviews_{$this->placeId}_6";
        return Cache::forget($cacheKey);
    }

    /**
     * Fallback reviews when API fails
     * 
     * @return array
     */
    protected function getFallbackReviews()
    {
        return [
            'success' => false,
            'reviews' => [],
            'total_ratings' => 0,
            'average_rating' => 0,
            'place_url' => $this->getPlaceUrl(),
            'error' => 'Unable to fetch reviews at this time. Please try again later.',
        ];
    }

    /**
     * Get business statistics
     * 
     * @return array
     */
    public function getBusinessStats()
    {
        $cacheKey = "google_maps_stats_{$this->placeId}";
        
        return Cache::remember($cacheKey, 3600, function () {
            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $this->placeId,
                    'fields' => 'name,rating,user_ratings_total,formatted_address,formatted_phone_number,website,opening_hours',
                    'key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    $result = $response->json()['result'] ?? [];
                    
                    return [
                        'name' => $result['name'] ?? '',
                        'rating' => $result['rating'] ?? 0,
                        'total_reviews' => $result['user_ratings_total'] ?? 0,
                        'address' => $result['formatted_address'] ?? '',
                        'phone' => $result['formatted_phone_number'] ?? '',
                        'website' => $result['website'] ?? '',
                        'is_open' => $result['opening_hours']['open_now'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error fetching business stats', ['error' => $e->getMessage()]);
            }

            return [];
        });
    }
}