<?php

namespace Services\Customer\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Console\Events\CommandStarting;
use Services\Customer\Contracts\ImporterContract;
use Symfony\Component\Console\Helper\ProgressBar;
use Services\Customer\Models\Import\CustomerImport;

class ImportCommand extends Command
{
    protected $description = 'Import users based on the drivere';

    protected $signature = 'customer:import
                            {--c|count=100 : How many users to import}';

    public function handle(ImporterContract $importer, Dispatcher $dispatcher)
    {
        $count = $this->option('count');
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        $this->advanceProgressBar($bar, $dispatcher);
        $importer->import(CustomerImport::class, compact('count'));
        $bar->finish();
        $this->info(PHP_EOL . __('customer::customer.import_success', compact('count')));
    }

    protected function advanceProgressBar(ProgressBar $bar, Dispatcher $dispatcher)
    {
        $dispatcher->listen('customer.import', function () use ($bar) {
            $bar->advance();
        });
    }

    protected function getNationalities() : array
    {
        return explode(',', $this->option('nationalities'));
    }
}
