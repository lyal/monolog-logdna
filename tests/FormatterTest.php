<?php
namespace Lyal\MonoLogLogDNA\Tests;

use Lyal\MonologLogDNA\LogDNAFormatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    private $logDNAFormatter;

    protected function setUp()
    {
        parent::setUp();
        $this->logDNAFormatter = new LogDNAFormatter();
    }

    public function testFormatResponse()
    {
        $record = [
            'message' => 'testing',
            'channel' => 'general',
            'level_name' => 'DEBUG',
            'context' => []
        ];

        $record = json_decode($this->logDNAFormatter->format($record))->lines{0};
        $this->assertEquals($record->env, 'unknown');
        $this->assertEquals($record->line, 'testing');
        $this->assertEquals($record->level, 'DEBUG');
        $this->assertEquals($record->meta, []);
        $this->assertEquals($record->app, 'general');
    }

    public function testBatchFormat()
    {
        $record = [
            [
                'message' => 'testing1',
                'channel' => 'general',
                'level_name' => 'DEBUG',
                'context' => []
            ],
            [
                'message' => 'testing2',
                'channel' => 'general',
                'level_name' => 'DEBUG',
                'context' => []
            ],

        ];

        $records = json_decode($this->logDNAFormatter->formatBatch($record))->lines;

        $this->assertEquals($records[0]->line, 'testing1');
        $this->assertEquals($records[1]->line, 'testing2');
    }
}
