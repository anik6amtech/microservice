<?php

/**
 * List of plain SQS queues and their corresponding handling classes
 */
return [
    'handlers' => [
        'base-integrations-updates' => App\Jobs\ProcessOrder::class,
    ],

    'default-handler' => App\Jobs\ProcessOrder::class
];
