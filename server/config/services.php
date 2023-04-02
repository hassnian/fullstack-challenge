<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'news_api' => [
        'key' => env('NEWS_API_KEY'),
        'url' => 'https://newsapi.org'
    ],

    'the_guardian' => [
        'key' => env('THE_GUARDIAN_API_KEY'),
        'url' => 'https://content.guardianapis.com'
    ],

    'ny_times_api' => [
        'key' => env('NY_TIMES_API_KEY'),
        'url' => 'https://api.nytimes.com/svc/search/v2'
    ],

    'search' => [
        'driver' => env('SEARCH_DRIVER', 'elasticsearch'),

        'elastic_search' => [
            'hosts' => explode(',',  env('ELASTICSEARCH_HOSTS', 'localhost:9200')),
        ],
    ],



];