<?php

namespace Spiral\Tests\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\Dataset\ChartJSDataset;
use Spiral\Statistics\Extract\Events;
use Spiral\Tests\BaseTest;

class ChartDatasetTest extends BaseTest
{
    public function testWithoutData()
    {
        $dataset = new ChartJSDataset();
        $pack = $dataset->pack();

        $this->assertCount(2, $pack);
        $this->assertEquals(['labels' => [], 'datasets' => []], $pack);
    }

    public function testSetDataWithoutParams()
    {
        $dataset = new ChartJSDataset([]);

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

        $this->assertEquals($pack['labels'], ['event label 1', 'event label 2']);
        $this->assertEquals($pack['datasets'], [
            ['data' => [1, 5]],
            ['data' => [2, 4]],
        ]);
    }

    public function testSetDataWithRows()
    {
        $params = [
            'event 1' => [
                'label' => 'event one',
            ],
            'event 2' => [
                'label' => 'event two',
            ],
        ];
        $dataset = new ChartJSDataset($params);

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

        $this->assertEquals($pack['labels'], ['event label 1', 'event label 2']);
        $this->assertEquals($pack['datasets'], [
            ['label' => 'event one', 'data' => [1, 5]],
            ['label' => 'event two', 'data' => [2, 4]],
        ]);
    }
}