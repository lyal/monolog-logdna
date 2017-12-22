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


    public function __construct(
        $key = null,
        $host = null,
        $mac = null,
        $ip = null,
        $httpClient = null,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
    
        parent::__construct($level, $bubble);
        $this->key = $key ?? getenv('LOGDNA_INGESTION_KEY');
        $this->carbon = new Carbon;
        $this->hostname = $host ?? getenv('LOGDNA_HOSTNAME') ?: gethostname();
        $this->mac = $mac;
        $this->ip = $ip ?? getenv('LOGDNA_HOST_IP') ?: gethostbyname(gethostname());


        $this->setHttpClient($httpClient ?? new Client());

        if (getenv('LOGDNA_API_URL')) {
            $this->api = getenv('LOGDNA_API_URL');
        }
    }

    /**
     * @param Client $client
     */

    protected function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }

    protected function write(array $record)
    {
        $response = $this->httpClient->post($this->api, [
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

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogDNAFormatter();
    }
}
