<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Extract\Events\Row;
use Spiral\Tests\BaseTest;

class EventsRowTest extends BaseTest
{
    public function testGetLabel()
    {
        $label = 'label';
        $row = new Row($label, []);

        $this->assertSame($label, $row->getLabel());
    }

    public function testGetEvents()
    {
        $label = 'label';
        $row = new Row($label, []);

        $this->assertSame([], $row->getEvents());

        $label = 'label';
        $row = new Row($label, ['event 1', 'event 2']);

        $this->assertSame(['event 1' => 0, 'event 2' => 0], $row->getEvents());
    }

    /**
     * @expectedException \Spiral\Statistics\Exceptions\InvalidExtractEventException
     */
    public function testAddUnknownEvent()
    {
        $label = 'label';
        $row = new Row($label, []);

        $this->assertSame([], $row->getEvents());

        $row->addEvent('some-event', 1);
    }

    public function testAddEvent()
    {
        $label = 'label';
        $row = new Row($label, ['event 1', 'event 2']);

        $this->assertSame(['event 1' => 0, 'event 2' => 0], $row->getEvents());

        $row->addEvent('event 1', 1.1);
        $row->addEvent('event 2', 2.2);
        $row->addEvent('event 1', 3.3);

        $this->assertArrayHasKey('event 1', $row->getEvents());
        $this->assertArrayHasKey('event 2', $row->getEvents());

        $this->assertCount(2, $row->getEvents());

        $this->assertSame(4.4, $row->getEvents()['event 1']);
        $this->assertSame(2.2, $row->getEvents()['event 2']);
    }
}