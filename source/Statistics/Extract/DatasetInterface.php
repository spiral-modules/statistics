<?php

namespace Spiral\Statistics\Extract;

interface DatasetInterface
{
    /**
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