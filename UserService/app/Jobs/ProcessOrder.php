<?php

namespace App\Jobs;

use App\Models\Emails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    protected $data;

    public function handle(Job $job, array $data)
    {
        // $data =$job->getRawBody();
        $this-> sendUserNotification($data);

    }

    public function sendUserNotification($order)
    {

        echo $order['product_id'];

       Emails::create(['user'=> $order['user_id'],'body' =>json_encode($order)]);

        // Decrease the product quantity

        return response(['message' => 'User Notified successfully'], 200);
    }
}
