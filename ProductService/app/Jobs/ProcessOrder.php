<?php

namespace App\Jobs;

use App\Models\Product;
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
        $this->decreaseProductQuantity($data);
        // $data =$job->getRawBody();

    }

    public function decreaseProductQuantity($order)
    {
        echo $order['product_id'];

        $product = Product::find($order['product_id']);


        // Decrease the product quantity
        $product->decrement('quantity', $order['quantity']);

        return response(['message' => 'Product quantity decreased successfully'], 200);
    }
}
