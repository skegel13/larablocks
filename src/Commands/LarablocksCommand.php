<?php

namespace Skegel13\Larablocks\Commands;

use Illuminate\Console\Command;

class LarablocksCommand extends Command
{
    public $signature = 'larablocks';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
