<?php

return [
    'email' => env('THINK_OCEAN_EMAIL', 'thinkocean@gmail.com'),
    'super_admin_email' => env('THINK_OCEAN_SUPER_ADMIN_EMAIL', 'ogeof49@gmail.com'),
    'defaults' => [
        'hero_title' => 'Healthier Oceans. Brighter Futures.',
        'hero_text' => 'Think Ocean is a UK-based community interest company working for a cleaner, healthier and more resilient ocean for people and the planet.',
        'mission_title' => 'Together for a sustainable ocean',
        'mission_text' => 'We believe in practical solutions, strong partnerships and the power of people. From coastal communities to global networks, we are building a healthier ocean for generations to come.',
        'registration_number' => '',
        'registered_office' => '',
        'volunteer_email' => 'thinkocean@gmail.com',
        'partner_email' => 'thinkocean@gmail.com',
        'contact_phone' => '',
        'contact_address' => '',
        'facebook_url' => '',
        'linkedin_url' => '',
        'instagram_url' => '',
        'youtube_url' => '',
        'live_enabled' => '1',
        'live_title' => 'Think Ocean TV',
        'live_description' => 'Watch ocean stories, conversations and live coverage of our projects and events.',
        'live_stream_url' => env('THINK_OCEAN_LIVE_STREAM_URL', 'https://rst5.saiuzwebnetwork.it:3635/hybrid/play.m3u8'),
        'live_schedule' => '',
    ],
];
