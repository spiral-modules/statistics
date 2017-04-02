<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Extract\Range;
use Spiral\Tests\BaseTest;

class DatetimeConverterTest extends BaseTest
{
    public function testImmutable()
    {
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);

        $datetime = new \DateTime();
        $immutable = $converter->immutable($datetime);

        $this->assertInstanceOf(\DateTimeImmutable::class, $immutable);
        $this->assertNotSame($datetime, $immutable);

        $datetime = new \DateTimeImmutable();
        $immutable = $converter->immutable($datetime);

        $this->assertInstanceOf(\DateTimeImmutable::class, $immutable);
        $this->assertSame($datetime, $immutable);
    }

    public function testImmutableViaConvert()
    {
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);

        $datetime1 = new \DateTime();
        $datetime2 = $converter->convert($datetime1, Range::DAILY);
        $datetime3 = $converter->convert($datetime1, Range::WEEKLY);
        $datetime4 = $converter->convert($datetime1, Range::MONTHLY);
        $datetime5 = $converter->convert($datetime1, Range::YEARLY);
        $datetime6 = $converter->convert($datetime1, 'some-unsupported-range');

        //Any other will be immutable also
        $this->assertInstanceOf(\DateTimeImmutable::class, $datetime6);

        $this->assertNotSame($datetime1, $datetime2);
        $this->assertNotSame($datetime1, $datetime3);
        $this->assertNotSame($datetime1, $datetime4);
        $this->assertNotSame($datetime1, $datetime5);
        $this->assertNotSame($datetime1, $datetime6);

        $this->assertNotSame($datetime2, $datetime3);
        $this->assertNotSame($datetime2, $datetime4);
        $this->assertNotSame($datetime2, $datetime5);
        $this->assertNotSame($datetime2, $datetime6);

        $this->assertNotSame($datetime3, $datetime4);
        $this->assertNotSame($datetime3, $datetime5);
        $this->assertNotSame($datetime3, $datetime6);

        $this->assertNotSame($datetime4, $datetime5);
        $this->assertNotSame($datetime4, $datetime6);

        $this->assertNotSame($datetime5, $datetime6);
    }

    public function testConvert()
    {
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);

        $datetime = new \DateTime('now');
        $immutable = new \DateTimeImmutable('now');

        $formatMonth = $immutable->format('m');
        $formatYear = $immutable->format('Y');
        $weekDay = $immutable->format('w') ? $immutable->format('w') - 1 : 6;

        $this->assertEquals(
            $immutable->getTimestamp(),
            $converter->convert($datetime)->getTimestamp()
        );
        $this->assertEquals(
            $immutable->setTime(0, 0, 0),
            $converter->convert($datetime, Range::DAILY)
        );
        $this->assertEquals(
            $immutable->sub(new \DateInterval('P' . $weekDay . 'D'))->setTime(0, 0, 0),
            $converter->convert($datetime, Range::WEEKLY)
        );
        $this->assertEquals(
            $immutable->setDate($formatYear, $formatMonth, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, Range::MONTHLY)
        );
        $this->assertEquals(
            $immutable->setDate($formatYear, 1, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, Range::YEARLY)
        );
        $this->assertEquals(
            $immutable,
            $converter->convert($datetime, 'some-unsupported-range')
        );
    }
}