# statistics
Statistics module. Allows to track internal events, extract them for charts.

[![Latest Stable Version](https://poser.pugx.org/spiral/statistics/v/stable)](https://packagist.org/packages/spiral/statistics) 
[![Total Downloads](https://poser.pugx.org/spiral/statistics/downloads)](https://packagist.org/packages/spiral/statistics) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral-modules/statistics/badges/quality-score.png)](https://scrutinizer-ci.com/g/spiral-modules/statistics/) 
[![Coverage Status](https://coveralls.io/repos/github/spiral-modules/statistics/badge.svg)](https://coveralls.io/github/spiral-modules/statistics)
[![Build Status](https://travis-ci.org/spiral-modules/statistics.svg?branch=master)](https://travis-ci.org/spiral-modules/statistics)

## Installation
```
composer require spiral/statistics
spiral register spiral/statistics
```

## Usage

### How to add event to statistics

You can both track a single event or a batch of events 

```php
/**
 * @var \Spiral\Statistics\Track $track
 */
$track->event('event name 1', 1, $datetime);
//OR
$track->events([
    'event name 2' => 2,
    'event name 3' => 3
],
    $datetime
);
```

> `$datetime` is optional `\DateTimeInterface` variable, "now" is by default

### How to get events for a given period of time

```php
/**
 * @var \Spiral\Statistics\Extract $extract
 * @var \Spiral\Statistics\Extract\RangeInterface $range
 * @var \Spiral\Statistics\Extract\Events $events
 *
 * $range is a grouping level, you can use one of supported ones, they are placed in
 * `\Spiral\Statistics\Extract\Range` namespace.
 */
$events = $extract->events($startDatetime, $endDatetime, $range, ['event name 1', 'event name 2']);
```
> `$startDatetime` and `$endDatetime` will be swapped if `$endDatetime` is less than `$startDatetime`.

As a result, you can receive an array of ` \Spiral\Statistics\Extract\Events\Row` objects, one row represents one range period.
```php
/** @var \Spiral\Statistics\Extract\Events\Row $row */
foreach ($events->getRows() as $row) {
    echo $row->getLabel();  // is a period label
    echo $row->getEvents(); // contains all summarized events

    //For month range output will be like:
    //Label: 'Jan, 2017'
    //Events: ['event name1 ' => 1, 'event name2 ' => 2]
}
```

### How to use fetched events

You can use fetched rows data as you wish, but we have included `\Spiral\Statistics\Extract\DatasetInterface` that can be useful if you're going to draw charts.
The main idea is to use `\Spiral\Statistics\Extract\Events` and convert presented data in a special way. Here's an `AbstractDataset` class that implements `DatasetInterface`:
```php
<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;
use Spiral\Statistics\Extract\Events;

abstract class AbstractDataset implements DatasetInterface
{
    /** @var Events\Row[] */
    protected $raw = [];

    /** @var array */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function setData(Events $events)
    {
        $this->raw = $events->getRows();
        $this->convert();
    }

    /**
     * Convert chart data if required.
     */
    abstract protected function convert();
}
```
```php
<?php

namespace Spiral\Statistics\Extract;

interface DatasetInterface
{
    /**
     * Set chart dataset data. Best place to convert it for current chart format.
     *
     * @param Events $events
     */
    public function setData(Events $events);

    /**
     * Pack data to chart usage.
     *
     * @return array
     */
    public function pack(): array;
}
```

This package contains 2 dataset implementations for [Chart.js](https://chartjs.org) and for [Google Charts](https://developers.google.com/chart/).
```php
/**
 * @var \Spiral\Statistics\Extract\Events $events
 */
$dataset = new \Spiral\Statistics\Extract\Dataset\ChartJsDataset([
   'event1' => [
       'label'           => 'event one',
   ],
   'event2' => [
       'label'           => 'event two',
   ],
]);
$dataset->setData($events);
print_r($dataset->pack());
```