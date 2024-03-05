<?php

namespace App\Console\Commands;

use App\RabbitMQService;
use Illuminate\Console\Command;

class ListenProductUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mq:listen-product-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqService = new RabbitMQService();
        $mqService->consume('product.update');
    }
}
