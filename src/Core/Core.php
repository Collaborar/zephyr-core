<?php

declare(strict_types=1);

namespace ZephyrCore\Core;

use Zephyr\Application\Application;

class Core
{
    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(protected Application $app)
    {
    }

    public function assets()
    {
        return 'assets';
    }
}
