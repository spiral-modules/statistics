<?php

namespace Spiral\Tests\Statistics\Sources;

use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Extract;
use Spiral\Tests\BaseTest;

/**
 * For \Spiral\Statistics\Database\Sources\OccurrenceSource::findByGroupedInterval tests see below:
 *
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testSamePeriodOccurrenceSourceFindByGroupedInterval
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testAnotherPeriodOccurrenceSourceFindByGroupedInterval
 */
class OccurrenceSourceTest extends BaseTest
{
    public function testFindByTimestamp()
    {
        $track = $this->getTrack();
        $source = $this->getSource();

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
        $source = $this->getSource();

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
        $source = $this->getSource();

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
        $source = $this->getSource();
        $converter = $this->getConverter();

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
}