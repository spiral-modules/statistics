<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Extract\Events;
use Spiral\Tests\BaseTest;

class EventsTest extends BaseTest
{
    public function testAddRow()
    {
        $events = new Events([]);
        $row = $events->addRow('some-row');

        $this->assertNotEmpty($row);
        $this->assertInstanceOf(Events\Row::class, $row);
    }

    public function testGetRows()
    {
        $events = new Events([]);

        $this->assertEmpty($events->getRows());

        $events->addRow('label 1');
        $events->addRow('label 2');

        $rows = $events->getRows();
        $this->assertCount(2, $rows);
        $this->assertArrayHasKey('label 1', $rows);
        $this->assertArrayHasKey('label 2', $rows);

        /**
         * @var Events\Row $row1
         * @var Events\Row $row2
         */
        $row1 = $rows['label 1'];
        $row2 = $rows['label 2'];

        $this->assertInstanceOf(Events\Row::class, $row1);
        $this->assertInstanceOf(Events\Row::class, $row2);
    }

    public function testPassEventsToRows()
    {
        $arr = ['event 1', 'event 2'];
        $events = new Events($arr);

        $this->assertEmpty($events->getRows());

        $events->addRow('label');

        $rows = $events->getRows();
        $this->assertCount(1, $rows);

        /** @var Events\Row $row */
        $row = $rows['label'];

        $this->assertArrayHasKey('event 1', $row->getEvents());
        $this->assertArrayHasKey('event 2', $row->getEvents());
    }
}