<?php

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Valentin V (vvval)
 */
class StatisticsModule implements ModuleInterface
{
    /**
     * @param RegistratorInterface $registrator
     */
    public function register(RegistratorInterface $registrator)
    {
        //Register tokenizer directory
        $registrator->configure('tokenizer', 'directories', 'spiral/statistics', [
            "directory('libraries') . 'spiral/statistics/source/Statistics/',",
        ]);

        //Register database settings
        $registrator->configure('databases', 'databases', 'spiral/statistics', [
            "'statistics' => [",
            "   'connection'  => 'mysql',",
            "   'tablePrefix' => 'statistics_'",
            "   /*{{databases.statistics}}*/",
            "],",
        ]);
    }

    /**
     * @param PublisherInterface   $publisher
     * @param DirectoriesInterface $directories
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
    }
}