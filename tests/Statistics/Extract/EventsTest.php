<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Extract\DatasetInterface;
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

    public function testPrepare()
    {
        $events = new Events([]);

        $dataset = \Mockery::mock(DatasetInterface::class);
        $dataset->shouldReceive('setData');

        /** @var DatasetInterface $dataset */
        $result = $events->prepare($dataset);

        $this->assertNotEmpty($result);
        $this->assertInstanceOf(DatasetInterface::class, $result);
        $this->assertSame($dataset, $result);
    }
}