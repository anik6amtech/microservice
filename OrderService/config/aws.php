<?php

return [
    'default' => 'us-east-1', // Or your preferred default region

    'regions' => [
        'local' => [
            'endpoint' => 'https://sqs.ap-southeast-1.amazonaws.com/533267049734', // Replace with your localstack endpoint if applicable
        ],
        // Add other regions as needed with their corresponding endpoint URLs
    ],

    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],

    'sns' => [
        'version' => 'latest',
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // Or your default region
    ],

    // Add configurations for other AWS services you might use (e.g., SQS, Lambda)
];
