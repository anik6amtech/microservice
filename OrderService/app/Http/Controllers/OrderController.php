<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrder;
use App\Models\Order;
use App\RabbitMQService;
use App\SnsService;
use Aws\Sns\SnsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{

    protected $rabbitMQService;
    // public function __construct(SnsService $rabbitMQService) {
    //     $this->rabbitMQService = $rabbitMQService;
    // }
    public function store(Request $request,SnsService $sns)
    {
        $token = $request->header('Authorization');

        $user = $this->getUser($token);
        $product = $this->getProduct($request->product_id);

        if ($user && $product) {

            $order = Order::create([
                'user_id' => $user['id'],
                'product_id' => $product['id'],
                'quantity' => $request->quantity,
                'total_price' => $product['price'] * $request->quantity,
            ]);

            $this->publish($order );
            // $sns->publish('arn:aws:sns:ap-southeast-1:533267049734:order_complete', 'https');
            // ProcessOrder::dispatch($order);
            // $this->rabbitMQService->publish(json_encode($order));
            return response(['order' => $order], 201);
        } else {
            if (!$user) {
                return response(['error' => 'User verification failed'], $user->status());
            } elseif (!$product) {
                return response(['error' => 'product verification failed'], $product->status());
            } else {
                return response(['error' => 'product / User not found'], 404);
            }
        }
    }

    private function getUser($token)
    {
        $response = Http::withHeaders(['Authorization' => $token])->get('http://127.0.0.1:8000/api/verify-user')->json('user');

        return $response;
    }
    private function getProduct($product_id)
    {
        $product = Http::get('http://127.0.0.1:8001/api/products/' . $product_id)->json('product');


        return $product;
    }

    public function publish($order ){
        $snsClient = new SnsClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $snsClient->publish([
            'TopicArn' => 'arn:aws:sns:ap-southeast-1:533267049734:order_complete',
            'Message' => json_encode($order),
        ]);
    }
    private function notifyUser(){

    }
}
