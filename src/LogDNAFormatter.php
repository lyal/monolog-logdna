<?php declare(strict_types = 1);
namespace Lyal\MonologLogDNA;

use Carbon\Carbon;
use Monolog\Formatter\JsonFormatter;

class LogDNAFormatter extends JsonFormatter
{
    private $carbon;
    private $environment;

    public function __construct($environment = null, $batchMode = self::BATCH_MODE_NEWLINES, $appendNewlines = false)
    {
        parent::__construct($batchMode, $appendNewlines);
        $this->carbon = new Carbon;
        $this->environment = $environment ?? 'unknown';
    }

    public function format(array $record) :string
    {
        $payload = [
            'lines' => [$this->formatLine($record)]
        ];
        return parent::format($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        $payload = ['lines' => []];

        foreach ($records as $record) {
            $payload['lines'][] = $this->formatLine($record);
        }

        return json_encode($payload);
    }

    private function formatLine(array $record) :array
    {
        return [
            'timestamp' => $this->carbon->getTimestamp(),
            'line' => $record['message'],
            'app' => $record['channel'],
            'level' => $record['level_name'],
            'env' => $this->environment,
            'meta' => $record['context']
        ];
    }
}
