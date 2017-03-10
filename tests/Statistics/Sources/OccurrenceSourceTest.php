<?php

namespace Spiral\Tests\Statistics\Sources;

use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Database\Sources\OccurrenceSource;
use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\ExtractRange;
use Spiral\Statistics\Track;
use Spiral\Tests\BaseTest;

class OccurrenceSourceTest extends BaseTest
{
    public function testFindByTimestamp()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);

        $datetime = new \DateTime('now');
        $occurrence = $source->findByTimestamp($datetime);

        $this->assertEmpty($occurrence);
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->event('some-event', 1.23, $datetime);
        $this->assertCount(1, $this->orm->source(Occurrence::class));

        $occurrence = $source->findByTimestamp($datetime);
        $this->assertNotEmpty($occurrence);
    }

    public function testGetByTimestamp()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);

        $datetime = new \DateTime('now');
        $occurrence = $source->findByTimestamp($datetime);

        $this->assertEmpty($occurrence);
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $occurrence = $source->getByTimestamp($datetime);
        $this->assertEmpty($occurrence->primaryKey());
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $occurrence->save();
        $this->assertCount(1, $this->orm->source(Occurrence::class));
    }

    public function testCreateFromTimestamp()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $datetime = new \DateTime('now');
        $occurrence = $source->createFromTimestamp($datetime);
        $this->assertEmpty($occurrence->primaryKey());
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $occurrence->save();
        $this->assertCount(1, $this->orm->source(Occurrence::class));
    }

    public function testDataIntegrity()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);

        $datetime = new \DateTime('now');
        $occurrence = $source->createFromTimestamp($datetime);
        $this->assertNotEmpty($occurrence);

        $this->assertEquals($occurrence->timestamp->getTimestamp(), $datetime->getTimestamp());
        $this->assertEquals(
            $occurrence->day_mark,
            $converter->convert($datetime, 'day')
        );
        $this->assertEquals(
            $occurrence->week_mark,
            $converter->convert($datetime, 'week')
        );
        $this->assertEquals(
            $occurrence->month_mark,
            $converter->convert($datetime, 'month')
        );
        $this->assertEquals(
            $occurrence->year_mark,
            $converter->convert($datetime, 'year')
        );
    }

    public function testFindByRangeDaily()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::DAILY);
        $datetime = new \DateTime('today noon');
        $converted = $converter->convert($datetime, Extract::DAILY);

        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $source->findByRange($range, $converted));
        $this->assertCount(1, $this->orm->source(Occurrence::class));

        $datetime = new \DateTime('today noon + 2 hours');
        $track->event('some-event', 1.23, $datetime);
        $converted = $converter->convert($datetime, Extract::DAILY);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $source->findByRange($range, $converted));
    }

    public function testFindByRangeWeekly()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::WEEKLY);
        $datetime = new \DateTime('today noon');
        $converted = $converter->convert($datetime, Extract::WEEKLY);

        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $source->findByRange($range, $converted));
        $this->assertCount(1, $this->orm->source(Occurrence::class));

        $datetime = new \DateTime('today noon + 2 hours');
        $track->event('some-event', 1.23, $datetime);
        $converted = $converter->convert($datetime, Extract::WEEKLY);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $source->findByRange($range, $converted));
    }

    public function testFindByRangeMonthly()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::MONTHLY);
        $datetime = new \DateTime('today noon');
        $converted = $converter->convert($datetime, Extract::MONTHLY);

        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $source->findByRange($range, $converted));
        $this->assertCount(1, $this->orm->source(Occurrence::class));

        $datetime = new \DateTime('today noon + 2 hours');
        $track->event('some-event', 1.23, $datetime);
        $converted = $converter->convert($datetime, Extract::MONTHLY);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $source->findByRange($range, $converted));
    }

    public function testFindByRangeYearly()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::YEARLY);
        $datetime = new \DateTime('today noon');
        $converted = $converter->convert($datetime, Extract::YEARLY);

        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $source->findByRange($range, $converted));
        $this->assertCount(1, $this->orm->source(Occurrence::class));

        $datetime = new \DateTime('today noon + 2 hours');
        $track->event('some-event', 1.23, $datetime);
        $converted = $converter->convert($datetime, Extract::YEARLY);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $source->findByRange($range, $converted));
    }

    public function testFindByRangeDailyWithEvents()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::DAILY);

        $datetime = new \DateTime('today noon');
        $datetime2 = new \DateTime('today noon + 2 hours');

        $converted = $converter->convert($datetime, Extract::DAILY);
        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->events(['some-event' => 1, 'some-event2' => 2, 'some-event3' => 2], $datetime);
        $track->events(['some-event3' => 3, 'some-event4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));

        $checkEvents = [
            ['some-event'],
            ['some-event', 'some-event2'],
            ['some-event', 'some-event3'],
            ['some-event', 'some-event2', 'some-event3', 'some-event4']
        ];
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[0]));
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[1]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[2]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[3]));
    }

    public function testFindByRangeWeeklyWithEvents()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::WEEKLY);

        $datetime = new \DateTime('today noon');
        $datetime2 = new \DateTime('today noon + 2 hours');

        $converted = $converter->convert($datetime, Extract::WEEKLY);
        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->events(['some-event' => 1, 'some-event2' => 2, 'some-event3' => 2], $datetime);
        $track->events(['some-event3' => 3, 'some-event4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));

        $checkEvents = [
            ['some-event'],
            ['some-event', 'some-event2'],
            ['some-event', 'some-event3'],
            ['some-event', 'some-event2', 'some-event3', 'some-event4']
        ];
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[0]));
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[1]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[2]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[3]));
    }

    public function testFindByRangeMonthlyWithEvents()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::MONTHLY);

        $datetime = new \DateTime('today noon');
        $datetime2 = new \DateTime('today noon + 2 hours');

        $converted = $converter->convert($datetime, Extract::MONTHLY);
        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->events(['some-event' => 1, 'some-event2' => 2, 'some-event3' => 2], $datetime);
        $track->events(['some-event3' => 3, 'some-event4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));

        $checkEvents = [
            ['some-event'],
            ['some-event', 'some-event2'],
            ['some-event', 'some-event3'],
            ['some-event', 'some-event2', 'some-event3', 'some-event4']
        ];
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[0]));
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[1]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[2]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[3]));
    }

    public function testFindByRangeYearlyWithEvents()
    {
        /** @var OccurrenceSource $source */
        $source = $this->container->get(OccurrenceSource::class);
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $range = new ExtractRange(Extract::YEARLY);

        $datetime = new \DateTime('today noon');
        $datetime2 = new \DateTime('today noon + 2 hours');

        $converted = $converter->convert($datetime, Extract::YEARLY);
        $this->assertCount(0, $source->findByRange($range, $converted));
        $this->assertCount(0, $this->orm->source(Occurrence::class));

        $track->events(['some-event' => 1, 'some-event2' => 2, 'some-event3' => 2], $datetime);
        $track->events(['some-event3' => 3, 'some-event4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));

        $checkEvents = [
            ['some-event'],
            ['some-event', 'some-event2'],
            ['some-event', 'some-event3'],
            ['some-event', 'some-event2', 'some-event3', 'some-event4']
        ];
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[0]));
        $this->assertCount(1, $source->findByRange($range, $converted, $checkEvents[1]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[2]));
        $this->assertCount(2, $source->findByRange($range, $converted, $checkEvents[3]));
    }
}