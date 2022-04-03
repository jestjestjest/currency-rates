<?php

namespace App\Console\Commands;

use App\Jobs\GetCurrenciesRatesJob;
use Illuminate\Console\Command;

class ImportCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:get {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateFrom = (string)$this->argument('dateFrom');
        $dateTo = (string)$this->argument('dateTo');

        $job = new GetCurrenciesRatesJob($dateFrom, $dateTo);
        $job->handle();
    }
}
