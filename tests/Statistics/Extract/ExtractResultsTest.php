<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractResultsException;
use Spiral\Statistics\Extract\ExtractResults;
use Spiral\Tests\BaseTest;

class ExtractResultsTest extends BaseTest
{
    public function testEmptyRow()
    {
        $events = [];
        $results = new ExtractResults($events);

        $this->expectException(InvalidExtractResultsException::class);
        $results->addEvent('event', 1);
    }

    public function testUnknownEvent()
    {
        $events = [];
        $results = new ExtractResults($events);
        $results->addRow('label');

        $this->expectException(InvalidExtractResultsException::class);
        $results->addEvent('event', 1);
    }

    public function testResults()
    {
        $events = ['event', 'event2'];
        $results = new ExtractResults($events);

        $results->addRow('label');
        $results->addEvent('event', 1);

        $results->addRow('label2');
        $results->addEvent('event2', 2);

        $this->assertArrayHasKey('label', $results->results());
        $this->assertArrayHasKey('label2', $results->results());

        $this->assertArrayHasKey('event', $results->results()['label']);
        $this->assertArrayHasKey('event2', $results->results()['label']);
        $this->assertEquals(1, $results->results()['label']['event']);
        $this->assertEquals(0, $results->results()['label']['event2']);

        $this->assertArrayHasKey('event', $results->results()['label2']);
        $this->assertArrayHasKey('event2', $results->results()['label2']);
        $this->assertEquals(0, $results->results()['label2']['event']);
        $this->assertEquals(2, $results->results()['label2']['event2']);
    }

    public function testEmptyResults()
    {
        $events = ['event', 'event2'];
        $results = new ExtractResults($events);

        $results->addRow('label');

        $results->addRow('label2');

        $this->assertArrayHasKey('label', $results->results());
        $this->assertArrayHasKey('label2', $results->results());

        $this->assertArrayHasKey('event', $results->results()['label']);
        $this->assertArrayHasKey('event2', $results->results()['label']);
        $this->assertEquals(0, $results->results()['label']['event']);
        $this->assertEquals(0, $results->results()['label']['event2']);

        $this->assertArrayHasKey('event', $results->results()['label2']);
        $this->assertArrayHasKey('event2', $results->results()['label2']);
        $this->assertEquals(0, $results->results()['label2']['event']);
        $this->assertEquals(0, $results->results()['label2']['event2']);
    }
}