<?php

namespace Spiral\Statistics\Extract;

interface DatasetInterface
{
    public function __construct(array $data, array $params);

    public function pack(): array;
}