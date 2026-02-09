<?php

namespace App\Services;

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InstagramFeedService
{
    protected $username;

    public function __construct()
    {
        $this->username = config('services.instagram.username', 'uditnee');
    }

    /**
     * Get Instagram feed posts
     * 
     * @param int $limit
     * @return array
     */
    public function getFeed($limit = 9)
    {
        try {
            $cacheKey = "instagram_feed_{$this->username}_{$limit}";
            
            return Cache::remember($cacheKey, 3600, function () use ($limit) {
                $api = new Api();
                $profile = $api->getProfile($this->username);
                $medias = $profile->getMedias();
                
                $posts = [];
                $count = 0;
                
                foreach ($medias as $media) {
                    if ($count >= $limit) break;
                    
                    $posts[] = [
                        'id' => $media->getId(),
                        'link' => $media->getLink(),
                        'image' => $media->getDisplaySrc(),
                        'thumbnail' => $media->getThumbnailSrc(),
                        'caption' => $media->getCaption() ?? '',
                        'likes' => $media->getLikes(),
                        'comments' => $media->getComments(),
                        'type' => $media->getTypeName(), // IMAGE, VIDEO, CAROUSEL
                        'timestamp' => $media->getDate()->getTimestamp(),
                    ];
                    
                    $count++;
                }
                
                return $posts;
            });
            
        } catch (InstagramException $e) {
            Log::error('Instagram Feed Error: ' . $e->getMessage());
            return $this->getFallbackPosts();
        } catch (\Exception $e) {
            Log::error('Instagram Feed Error: ' . $e->getMessage());
            return $this->getFallbackPosts();
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        $cacheKey = "instagram_feed_{$this->username}_9";
        Cache::forget($cacheKey);
    }

    /**
     * Fallback posts when API fails
     */
    protected function getFallbackPosts()
    {
        return [
            [
                'id' => '1',
                'link' => 'https://instagram.com/' . $this->username,
                'image' => 'https://via.placeholder.com/400x400?text=Instagram+Post+1',
                'thumbnail' => 'https://via.placeholder.com/400x400?text=Instagram+Post+1',
                'caption' => 'Follow us on Instagram!',
                'likes' => 0,
                'comments' => 0,
                'type' => 'IMAGE',
                'timestamp' => time(),
            ],
            // Add more fallback posts if needed
        ];
    }

    /**
     * Get profile info
     */
    public function getProfile()
    {
        try {
            return Cache::remember("instagram_profile_{$this->username}", 3600, function () {
                $api = new Api();
                $profile = $api->getProfile($this->username);
                
                return [
                    'username' => $profile->getUserName(),
                    'full_name' => $profile->getFullName(),
                    'bio' => $profile->getBiography(),
                    'followers' => $profile->getFollowers(),
                    'following' => $profile->getFollowing(),
                    'posts_count' => $profile->getMediaCount(),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Instagram Profile Error: ' . $e->getMessage());
            return null;
        }
    }
}