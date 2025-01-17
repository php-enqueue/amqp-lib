<?php

namespace Enqueue\AmqpLib\Tests\Spec;

use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\AmqpLib\AmqpContext;
use Interop\Queue\Context;
use Interop\Queue\Spec\SendToAndReceiveFromQueueSpec;

/**
 * @group functional
 */
class AmqpSslSendToAndReceiveFromQueueTest extends SendToAndReceiveFromQueueSpec
{
    protected function createContext()
    {
        $baseDir = realpath(__DIR__.'/../../../../');

        // guard
        $this->assertNotEmpty($baseDir);

        $certDir = $baseDir.'/var/rabbitmq_certificates';
        $this->assertDirectoryExists($certDir);

        $factory = new AmqpConnectionFactory([
            'dsn' => getenv('AMQPS_DSN'),
            'ssl_verify' => false,
            'ssl_cacert' => $certDir.'/cacert.pem',
            'ssl_cert' => $certDir.'/cert.pem',
            'ssl_key' => $certDir.'/key.pem',
        ]);

        return $factory->createContext();
    }

    /**
     * @param AmqpContext $context
     */
    protected function createQueue(Context $context, $queueName)
    {
        $queue = $context->createQueue($queueName);
        $context->declareQueue($queue);
        $context->purgeQueue($queue);

        return $queue;
    }
}
