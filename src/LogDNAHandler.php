<?php declare(strict_types = 1);
namespace Lyal\MonologLogDNA;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LogDNAHandler extends AbstractProcessingHandler
{
    private $carbon;

    protected $key;
    protected $hostname;
    protected $mac;
    protected $ip;

    protected $httpClient;

    protected $api = 'https://logs.logdna.com/logs/ingest';


    /**
     * LogDNAHandler constructor.
     * @param null $key
     * @param null $host
     * @param null $mac
     * @param null $ip
     * @param null $httpClient
     * @param int $level
     * @param bool $bubble
     */


    public function __construct(
        $key = null,
        $host = null,
        $mac = null,
        $ip = null,
        $httpClient = null,
        $level = Logger::DEBUG,
        $bubble = true
    )
    {

        parent::__construct($level, $bubble);
        $this->key = $key ?? getenv('LOGDNA_INGESTION_KEY');
        $this->carbon = new Carbon;
        $this->hostname = $host ?? getenv('LOGDNA_HOSTNAME') ?: gethostname();
        $this->mac = $mac;
        $this->ip = $ip ?? getenv('LOGDNA_HOST_IP') ?: gethostbyname(gethostname());
        $this->setHttpClient($httpClient ?? new Client());
        $this->api = getenv('LOGDNA_API_URL') ?: $this->api;
    }

    /**
     * @param Client $client
     */

    protected function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Attempts to write a log item once to the LogDNA api service
     *
     * It's possible that we should try this 5 times as other libraries do
     *
     * @param array $record
     */

    protected function write(array $record)
    {
        $this->httpClient->post($this->api, [
            'headers' => [
                'Content-Type' => 'application/json',

            ],
            'query' => [
                'hostname' => $this->hostname,
                'mac' => $this->mac,
                'ip' => $this->ip,
                'now' => $this->carbon->getTimestamp()
            ],
            'auth' => [$this->key, ''],
            'body' => $record['formatted']
        ]);
    }

    /**
     * Set the default formatter to our own
     *
     * @return FormatterInterface
     */

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogDNAFormatter();
    }
}
