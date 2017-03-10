<?php

namespace Spiral\Statistics;

class DatetimeConverter
{
    /**
     * @param \DateTime   $datetime
     * @param string|null $interval
     * @return \DateTime
     */
    public function convert(\DateTime $datetime, string $interval = null): \DateTime
    {
        $datetime = clone $datetime;

        switch ($interval) {
            case 'day':
                $datetime->setTime(0, 0, 0);
                break;

            case 'week':
                $weekSub = $datetime->format('w') ? $datetime->format('w') - 1 : 6;
                $datetime
                    ->sub(new \DateInterval('P' . $weekSub . 'D'))->setTime(0, 0, 0)
                    ->setTime(0, 0, 0);
                break;

            case 'month':
                $datetime
                    ->setDate($datetime->format('Y'), $datetime->format('m'), 1)
                    ->setTime(0, 0, 0);
                break;

            case 'year':
                $datetime
                    ->setDate($datetime->format('Y'), 1, 1)
                    ->setTime(0, 0, 0);
                break;
        }

        return $datetime;
    }
}