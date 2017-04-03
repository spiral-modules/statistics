<?php

namespace Spiral\Statistics\Extract;

interface DatasetInterface
{
    /**
     * Set chart dataset data. Best place to convert it for current chart format.
     *
     * @param Events $events
     */
    public function setData(Events $events);

    /**
     * Pack data to chart usage.
     *
     * @return array
     */
    public function pack(): array;
}