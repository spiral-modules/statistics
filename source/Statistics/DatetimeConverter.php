<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Extract\RangeInterface;

class DatetimeConverter
{
    /**
     * @param \DateTimeInterface  $datetime
     * @param RangeInterface $range
     * @return \DateTimeImmutable
     */
    public function convert(
        \DateTimeInterface $datetime,
        RangeInterface $range
    ): \DateTimeImmutable
    {
        $datetime = $this->immutable($datetime);

        switch ($range->getRange()) {
            case 'daily':
                return $datetime->setTime(0, 0, 0);

            case 'weekly':
                $weekSub = $datetime->format('w') ? $datetime->format('w') - 1 : 6;

                return $datetime
                    ->sub(new \DateInterval('P' . $weekSub . 'D'))
                    ->setTime(0, 0, 0);

            case 'monthly':
                return $datetime
                    ->setDate($datetime->format('Y'), $datetime->format('m'), 1)
                    ->setTime(0, 0, 0);

            case 'yearly':
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