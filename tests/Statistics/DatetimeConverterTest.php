<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Extract\Range\DailyRange;
use Spiral\Statistics\Extract\Range\MonthlyRange;
use Spiral\Statistics\Extract\Range\WeeklyRange;
use Spiral\Statistics\Extract\Range\YearlyRange;
use Spiral\Tests\BaseTest;
use Spiral\Tests\Statistics\Entities\UnknownRange;

class DatetimeConverterTest extends BaseTest
{
    public function testImmutable()
    {
        $converter = $this->getConverter();

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
        $converter = $this->getConverter();

        $datetime1 = new \DateTime();
        $datetime2 = $converter->convert($datetime1, new DailyRange());
        $datetime3 = $converter->convert($datetime1, new WeeklyRange());
        $datetime4 = $converter->convert($datetime1, new MonthlyRange());
        $datetime5 = $converter->convert($datetime1, new YearlyRange());
        $datetime6 = $converter->convert($datetime1, new UnknownRange());

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
        $converter = $this->getConverter();

        $datetime = new \DateTime('now');
        $immutable = new \DateTimeImmutable('now');

        $formatMonth = $immutable->format('m');
        $formatYear = $immutable->format('Y');
        $weekDay = $immutable->format('w') ? $immutable->format('w') - 1 : 6;

        $this->assertEquals(
            $immutable->setTime(0, 0, 0),
            $converter->convert($datetime, new DailyRange())
        );
        $this->assertEquals(
            $immutable->sub(new \DateInterval('P' . $weekDay . 'D'))->setTime(0, 0, 0),
            $converter->convert($datetime, new WeeklyRange())
        );
        $this->assertEquals(
            $immutable->setDate($formatYear, $formatMonth, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, new MonthlyRange())
        );
        $this->assertEquals(
            $immutable->setDate($formatYear, 1, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, new YearlyRange())
        );
        $this->assertEquals(
            $immutable,
            $converter->convert($datetime, new UnknownRange())
        );
    }
}