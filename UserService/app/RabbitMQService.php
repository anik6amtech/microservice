<?php
namespace App;

use App\Models\Emails;
use App\Models\Product;
use App\Models\RabbitMQ;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService{
    public function publish($message, $orderId)
    {
        try {
            // Replace with your actual connection details
            $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
            $channel = $connection->channel();

            // Exchange name and type based on your requirements
            $exchangeName = 'order.completion';
            $exchangeType = 'direct';

            // Declare the exchange only if it doesn't exist
            $channel->exchange_declare($exchangeName, $exchangeType, true, false, false);

            // Routing key with variable for order ID
            $routingKey = "order.completed";

            $msg = new AMQPMessage($message);
            $channel->basic_publish($msg, $exchangeName, $routingKey);
            echo " [x] Sent $message to $exchangeName / $routingKey.\n";

            $channel->close();
            $connection->close();
        } catch (\Throwable $e) {
            // Handle potential errors here (e.g., logging, retry logic)
            echo "Error publishing message: " . $e->getMessage() . "\n";
        }
    }
    public function consume($queue)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest','/');
        $channel = $connection->channel();
        $callback = function ($msg) {
            $this->sendUserNotification($msg);

        };
        $channel->queue_declare($queue, true, false, false, false);
        $channel->basic_consume($queue, '', true, true, false, false, $callback);
        echo 'Waiting for new message on',$queue, " \n" ;
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
    public function sendUserNotification($msg)
    {

        $order = json_decode($msg->body);
        echo $order->product_id;

       Emails::create(['user'=> $order->user_id,'body' =>$msg->body]);

        // Decrease the product quantity

        return response(['message' => 'User Notified successfully'], 200);
    }
}
