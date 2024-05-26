<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class TestAppCommand extends Command
{
    protected $signature = 'test:app';

    protected $description = 'Test Command';

    public function handle(): int
    {
        try {
            $this->info("Starting test\n");

            $this->info("\nDone");

            return 0;
        } catch (Exception $e) {
            $this->error('Error Testing');
            $this->error($e->getMessage());
            $this->line('');

            return 1;
        }
    }
}
