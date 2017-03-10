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
$track->event($eventName, $eventValue, $datetime);
//OR
$track->events([
    $eventName1 => $eventValue1,
    $eventName2 => $eventValue2
],
    $datetime
);
```

> `$datetime` is optional, "now" is by default

### How to get events for a given period of time

```php
/**
 * @var \Spiral\Statistics\Extract $extract
 * @var \Spiral\Statistics\Extract\ExtractResults $results
 */
$results = $extract->event($startDatetime, $endDatetime, $range, $events);
```
> `$startDatetime` and `$endDatetime` will be swapped if `$endDatetime` is less than `$startDatetime`.
> `$range` is an aggregation level, you can use one of supported ones: "day", "week", "month", "year"

As a result, you will receive an array, for example, for month range:

```php
[
    'Jan, 2017' => [
        'eventName1' => 1,
        'eventName2' => 2,
    ],
    'Feb, 2017' => [
        'eventName1' => 0,
        'eventName2' => 0.47,
    ],
];
```