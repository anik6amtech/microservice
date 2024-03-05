<?php

namespace App\Console\Commands;

use App\RabbitMQService;
use Illuminate\Console\Command;

class MQConsumerCommand extends Command
{
    protected $signature = 'mq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the mq queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $mqService = new RabbitMQService();
        $mqService->consume('product.update');
        $mqService->consume('user.notification');
    }
}
