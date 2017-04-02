<?php

namespace Spiral\Statistics;

class DatetimeConverter
{
    /**
     * @param \DateTimeInterface $datetime
     * @param string|null        $interval
     * @return \DateTimeImmutable
     */
    public function convert(
        \DateTimeInterface $datetime,
        string $interval = null
    ): \DateTimeImmutable
    {
        $datetime = $this->immutable($datetime);

        switch ($interval) {
            case 'day':
                return $datetime->setTime(0, 0, 0);

            case 'week':
                $weekSub = $datetime->format('w') ? $datetime->format('w') - 1 : 6;

                return $datetime
                    ->sub(new \DateInterval('P' . $weekSub . 'D'))
                    ->setTime(0, 0, 0);
                break;

            case 'month':
                return $datetime
                    ->setDate($datetime->format('Y'), $datetime->format('m'), 1)
                    ->setTime(0, 0, 0);
                break;

            case 'year':
                return $datetime
                    ->setDate($datetime->format('Y'), 1, 1)
                    ->setTime(0, 0, 0);
        }

        return $datetime;
    }

    /**
     * @param \DateTimeInterface $datetime
     * @return \DateTimeImmutable
     */
    public function immutable(\DateTimeInterface $datetime): \DateTimeImmutable
    {
        if ($datetime instanceof \DateTime) {
            return \DateTimeImmutable::createFromMutable($datetime);
        }

        return $datetime;
    }
}