<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractResultsException;
use Spiral\Statistics\Extract\Events;
use Spiral\Tests\BaseTest;

class ResultsTest extends BaseTest
{
    public function testEmptyRow()
    {
        $events = [];
        $extractEvents = new Events($events);

        $this->expectException(InvalidExtractResultsException::class);
        $extractEvents->addEvent('event', 1);
    }

    public function testUnknownEvent()
    {
        $events = [];
        $extractEvents = new Events($events);
        $extractEvents->addRow('label');

        $this->expectException(InvalidExtractResultsException::class);
        $extractEvents->addEvent('event', 1);
    }

    public function testResults()
    {
        $events = ['event', 'event2'];
        $extractEvents = new Events($events);

        $extractEvents->addRow('label');
        $extractEvents->addEvent('event', 1);

        $extractEvents->addRow('label2');
        $extractEvents->addEvent('event2', 2);

        $this->assertArrayHasKey('label', $extractEvents->results());
        $this->assertArrayHasKey('label2', $extractEvents->results());

        $this->assertArrayHasKey('event', $extractEvents->results()['label']);
        $this->assertArrayHasKey('event2', $extractEvents->results()['label']);
        $this->assertEquals(1, $extractEvents->results()['label']['event']);
        $this->assertEquals(0, $extractEvents->results()['label']['event2']);

        $this->assertArrayHasKey('event', $extractEvents->results()['label2']);
        $this->assertArrayHasKey('event2', $extractEvents->results()['label2']);
        $this->assertEquals(0, $extractEvents->results()['label2']['event']);
        $this->assertEquals(2, $extractEvents->results()['label2']['event2']);
    }

    public function testEmptyResults()
    {
        $events = ['event', 'event2'];
        $extractEvents = new Events($events);

        $extractEvents->addRow('label');

        $extractEvents->addRow('label2');

        $this->assertArrayHasKey('label', $extractEvents->results());
        $this->assertArrayHasKey('label2', $extractEvents->results());

        $this->assertArrayHasKey('event', $extractEvents->results()['label']);
        $this->assertArrayHasKey('event2', $extractEvents->results()['label']);
        $this->assertEquals(0, $extractEvents->results()['label']['event']);
        $this->assertEquals(0, $extractEvents->results()['label']['event2']);

        $this->assertArrayHasKey('event', $extractEvents->results()['label2']);
        $this->assertArrayHasKey('event2', $extractEvents->results()['label2']);
        $this->assertEquals(0, $extractEvents->results()['label2']['event']);
        $this->assertEquals(0, $extractEvents->results()['label2']['event2']);
    }
}