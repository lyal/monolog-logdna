<?php declare(strict_types = 1);
namespace Lyal\MonologLogDNA;

use Carbon\Carbon;
use Monolog\Formatter\JsonFormatter;

class LogDNAFormatter extends JsonFormatter
{
    private $carbon;
    private $environment;

    /**
     * LogDNAFormatter constructor.
     * @param null $environment
     * @param bool|int $batchMode
     * @param bool $appendNewlines
     */

    public function __construct($environment = null, $batchMode = self::BATCH_MODE_NEWLINES, $appendNewlines = false)
    {
        parent::__construct($batchMode, $appendNewlines);
        $this->carbon = new Carbon;
        $this->environment = $environment ?? getenv('APP_ENV') ?: 'unknown';
    }

    /**
     * Format a single record
     *
     * @param array $record
     * @return string
     */

    public function format(array $record) :string
    {
        $payload = [
            'lines' => [$this->formatLine($record)]
        ];
        return parent::format($payload);
    }


    /**
     * Batch a group of records, returning
     * a json string
     *
     * @param array $records
     * @return string
     */

    public function formatBatch(array $records)
    {
        $payload = ['lines' => []];

        foreach ($records as $record) {
            $payload['lines'][] = $this->formatLine($record);
        }

        return json_encode($payload);
    }

    /**
     * Helper function to format an individual log line for LogDNA
     *
     * @param array $record
     * @return array
     */

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
