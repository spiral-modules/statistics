<?php

namespace Spiral\Statistics\Database\Sources;

use Spiral\ORM\Entities\RecordSource;
use Spiral\Statistics\Database\Event;

class EventSource extends RecordSource
{
    const RECORD = Event::class;
}