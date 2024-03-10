<?php

namespace App;

use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;

class SnsService
{
    protected $snsClient;

    public function __construct()
    {
        // Initialize AWS SNS client
        $this->snsClient = new SnsClient([
            'region'      => config('services.sns.region'),
            'version'     => 'latest',
            'credentials' => [
                'key'    => config('services.sns.key'),
                'secret' => config('services.sns.secret'),
            ],
        ]);
    }

    /**
     * Subscribe an endpoint to a topic
     *
     * @param string $topicArn
     * @param string $protocol
     * @param string $endpoint
     * @return \Aws\Result
     */
    public function subscribe($topicArn, $protocol, $endpoint)
    {
        return $this->snsClient->subscribe([
            'TopicArn' => $topicArn,
            'Protocol' => $protocol,
        ]);
    }

    /**
     * Publish a message to an SNS topic
     *
     * @param string $topicArn
     * @param string $message
     * @return \Aws\Result
     */
    public function publish($topicArn, $message)
    {
        return $this->snsClient->publish([
            'TopicArn' => $topicArn,
            'Message'  => $message,
        ]);
    }

    /**
     * Create an SNS topic
     *
     * @param string $name
     * @return \Aws\Result
     */
    public function createTopic($name)
    {
        return $this->snsClient->createTopic([
            'Name' => $name,
        ]);
    }

    /**
     * Delete an SNS topic
     *
     * @param string $topicArn
     * @return \Aws\Result
     */
    public function deleteTopic($topicArn)
    {
        return $this->snsClient->deleteTopic([
            'TopicArn' => $topicArn,
        ]);
    }


    public function receiveMessages($queueUrl, $maxMessages = 1)
    {

        try {
            // Receive messages from the SQS queue
            $result = $this->snsClient->receiveMessage([
                'QueueUrl' => $queueUrl,
                'MaxNumberOfMessages' => $maxMessages,
            ]);

            // Extract the received messages
            $messages = $result->get('Messages');

            // Process the received messages (e.g., extract message body)
            $processedMessages = [];
            foreach ($messages as $message) {
                $body = json_decode($message['Body'], true);
                $processedMessages[] = $body;
            }

            return $processedMessages;
        } catch (\Exception $e) {
            // Handle receiving messages error
            return [];
        }
    }
}
