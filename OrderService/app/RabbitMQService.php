<?php
namespace App;

use App\Models\RabbitMQ;
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
                echo ' [x] Received ', $msg->body, "\n";

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

}
