<?php

namespace Spiral\Statistics\Extract;

interface DatasetInterface
{
    /**
     * Set chart dataset data. Best place to convert it for current chart format.
     *
     * @param array $data
     */
    public function setData(array $data);

    /**
     * Pack data to chart usage.
     *
     * @return array
     */
    public function pack(): array;
}