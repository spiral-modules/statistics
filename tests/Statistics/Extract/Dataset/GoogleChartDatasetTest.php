<?php

namespace Spiral\Tests\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\Dataset\GoogleChartDataset;
use Spiral\Statistics\Extract\Events;
use Spiral\Tests\BaseTest;

class GoogleChartDatasetTest extends BaseTest
{
    public function testLabels()
    {
        $labels = ['label 1', 'label 2'];
        $dataset = new GoogleChartDataset($labels);
        $pack = $dataset->pack();

        $this->assertCount(1, $pack);
        $this->assertEquals($pack[0], $labels);
    }

    public function testSetDataWithoutRows()
    {
        $labels = ['label 1', 'label 2'];
        $dataset = new GoogleChartDataset($labels);

        $events = new Events(['event 1', 'event 2']);

        $dataset->setData($events);

        $pack = $dataset->pack();
        $this->assertCount(1, $pack);
        $this->assertEquals($pack[0], $labels);
    }

    public function testSetDataWithRows()
    {
        $labels = ['label 1', 'label 2'];
        $dataset = new GoogleChartDataset($labels);

        $events = new Events(['event 1', 'event 2']);
        $row = $events->addRow('event label 1');
        $row->addEvent('event 1', 1);
        $row->addEvent('event 2', 1);
        $row->addEvent('event 2', 1);

        $row = $events->addRow('event label 2');
        $row->addEvent('event 2', 4);
        $row->addEvent('event 1', 3);
        $row->addEvent('event 1', 2);

        $dataset->setData($events);

        $pack = $dataset->pack();

        $this->assertCount(3, $pack);
        $this->assertEquals($pack[0], $labels);
        $this->assertEquals($pack[1], ['event label 1', 1, 2]);
        $this->assertEquals($pack[2], ['event label 2', 5, 4]);
    }
}