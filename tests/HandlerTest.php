<?php
namespace Lyal\MonoLogLogDNA\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Lyal\MonologLogDNA\LogDNAHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    private $logDNAHandler;
    private $logger;
    private $container = [];

    protected function setUp()
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, []),
        ]);

        $history = Middleware::history($this->container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);
        $this->logDNAHandler = new LogDNAHandler($key = 'testingkey', 'test', 'test', '192.1.1.1', $client);
        $this->logger = new Logger('general');
        $this->logger->pushHandler($this->logDNAHandler);
    }

    public function testHandlerWrite()
    {
        $this->logger->debug('test');
        $this->assertEquals($this->container[0]['response']->getStatusCode(), 200);
    }
}
