<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\DatetimeConverter;
use Spiral\Tests\BaseTest;

class DatetimeConverterTest extends BaseTest
{
    public function testMe()
    {
        /** @var DatetimeConverter $converter */
        $converter = $this->container->get(DatetimeConverter::class);

        $datetime = new \DateTime('now');
        $datetimeI = new \DateTimeImmutable('now');

        $formatM = $datetimeI->format('m');
        $formatY = $datetimeI->format('Y');
        $weekSub = $datetimeI->format('w') ? $datetimeI->format('w') - 1 : 6;

        $this->assertEquals($datetimeI, $converter->convert($datetime));
        $this->assertEquals(
            $datetimeI->setTime(0, 0, 0),
            $converter->convert($datetime, 'day')
        );
        $this->assertEquals(
            $datetimeI->sub(new \DateInterval('P' . $weekSub . 'D'))->setTime(0, 0, 0),
            $converter->convert($datetime, 'week')
        );
        $this->assertEquals(
            $datetimeI->setDate($formatY, $formatM, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, 'month')
        );
        $this->assertEquals(
            $datetimeI->setDate($formatY, 1, 1)->setTime(0, 0, 0),
            $converter->convert($datetime, 'year')
        );
    }
}