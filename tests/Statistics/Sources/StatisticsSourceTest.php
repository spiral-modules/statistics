<?php

namespace Spiral\Tests\Statistics\Sources;

use Spiral\Statistics\Database\Statistics;
use Spiral\Statistics\Extract;
use Spiral\Tests\BaseTest;

/**
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testSamePeriodSourceFindExtract
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testAnotherPeriodSourceFindExtract
 */
class StatisticsSourceTest extends BaseTest
{
    public function testFindExtractWithEvents()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime();
        $track->events(['some-event' => 1.23, 'some-event2' => 2.34], $datetime);

        $this->assertCount(2, $this->orm->source(Statistics::class));

        $this->assertCount(2, $this->getSource()
            ->findExtract(new \DateTime('-1 day'), new \DateTime('+1 day'), []));

        $this->assertCount(1, $this->getSource()
            ->findExtract(new \DateTime('-1 day'), new \DateTime('+1 day'), ['some-event']));

        $this->assertCount(0, $this->getSource()
            ->findExtract(new \DateTime('-1 day'), new \DateTime('+1 day'), ['some-event3']));
    }

    public function testFindExtractWithDateRange()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime();
        $track->events(['some-event' => 1.23, 'some-event2' => 2.34], $datetime);

        $this->assertCount(2, $this->orm->source(Statistics::class));

        $this->assertCount(0, $this->getSource()
            ->findExtract(new \DateTime('-3 day'), new \DateTime('-1 day'), []));

        $this->assertCount(0, $this->getSource()
            ->findExtract(new \DateTime('-3 day'), new \DateTime('-1 day'), ['some-event']));

        $this->assertCount(0, $this->getSource()
            ->findExtract(new \DateTime('-3 day'), new \DateTime('-1 day'), ['some-event3']));
    }

    public function testFindByEventNameAndDatetime()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime();
        $track->events(['some-event' => 1.23, 'some-event2' => 2.34], $datetime);

        $this->assertCount(2, $this->orm->source(Statistics::class));

        $this->assertEmpty(
            $this->getSource()->findByEventNameAndDatetime('some-event3', $datetime));

        $this->assertEmpty(
            $this->getSource()->findByEventNameAndDatetime('some-event', new \DateTime('+1 day')));

        $record = $this->getSource()->findByEventNameAndDatetime('some-event', $datetime);

        $this->assertNotEmpty($record);

        $this->assertEquals($record->name, 'some-event');
        $this->assertEquals($record->value, 1.23);
        $this->assertEquals(
            $record->timestamp->getTimestamp(),
            $datetime->getTimestamp()
        );
    }

    public function testDataIntegrity()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime();
        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $record */
        $record = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($record);

        $this->assertEquals($record->name, 'some-event');
        $this->assertEquals($record->value, 1.23);
        $this->assertEquals(
            $record->timestamp->getTimestamp(),
            $datetime->getTimestamp()
        );
    }
}