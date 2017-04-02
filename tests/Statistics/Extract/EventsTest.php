<?php

namespace Spiral\Tests\Statistics\Extract;

use Mockery\MockInterface;
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

        /** @var DatasetInterface|MockInterface $dataset */
        $dataset = \Mockery::mock(DatasetInterface::class);
        $dataset->shouldReceive('setData');

        $result = $events->prepare($dataset);

        $this->assertNotEmpty($result);
        $this->assertInstanceOf(DatasetInterface::class, $result);
        $this->assertSame($dataset, $result);
    }
}