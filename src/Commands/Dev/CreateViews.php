<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Commands\Dev;

use Illuminate\Console\Command;

class CreateViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-dev:make-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates views for the dummy provider and the shared provider';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
