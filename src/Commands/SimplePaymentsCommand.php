<?php

namespace Roelofr\SimplePayments\Commands;

use Illuminate\Console\Command;

class SimplePaymentsCommand extends Command
{
    public $signature = 'payments';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
