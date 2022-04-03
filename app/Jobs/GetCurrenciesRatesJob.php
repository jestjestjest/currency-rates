<?php

namespace App\Jobs;

use App\Models\BaseCurrency;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\True_;
use function PHPUnit\Framework\isEmpty;

class GetCurrenciesRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $dateFrom;
    protected string $dateTo;
    protected string $symbols = 'AMD,BYN,GBP,KZT';
    protected array  $existedDates;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $dateFrom = '', string $dateTo = '')
    {
        $this->dateFrom = Carbon::parse($dateFrom)->format('Y-m-d');
        $this->dateTo = Carbon::parse($dateTo)->format('Y-m-d');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $data = $this->getData();
        $this->deleteOldDates($this->getDates());
        $this->insertData($data);
    }

    protected function getData() : array
    {
        $apiKey = env('CURRENCY_API_KEY');

        $requestDates = $this->getDates();

        $requestParams = [
            'access_key' => $apiKey,
            'symbols' => $this->symbols,
        ];

        $baseCurrencies = $this->getBaseCurrenciesNames();
        $finalData = [];
        foreach ($baseCurrencies as $currencyId => $currencyName) {

            foreach ($requestDates as $date) {
                $request = Http::get('http://api.exchangeratesapi.io/v1/' . $date, Arr::add($requestParams, 'base', $currencyName));
                $requestArr = $request->json();

                if (isset($requestArr['success']) && !$requestArr['success']) {
                    Log::debug(json_encode($requestArr['error']));
                    continue;
                }

                if (!isset($requestArr['success'])) {
                    Log::debug('API не отвечает');
                    continue;
                }

                foreach ($requestArr['rates'] as $curName => $rate) {
                    $finalData[$currencyName][] = [
                        'name' => $curName,
                        'rate_value' => $rate,
                        'rate_date' => $requestArr['date'],
                        'base_currency_id' => $currencyId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

        }

        return $finalData;
    }

    protected function getBaseCurrenciesNames() : array
    {
        return BaseCurrency::all()->pluck('name', 'id')->toArray();
    }

    protected function getDates() : array
    {
        $dates = [];

        if ($this->dateFrom && $this->dateTo) {
            $period = CarbonPeriod::create($this->dateFrom, $this->dateTo);
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        } else {
            $dates[] = Carbon::now()->format('Y-m-d');
        }

        return $dates;
    }

    protected function insertData(array $data) : void
    {
            foreach ($data as $currency => $rates) {
                BaseCurrency::where('name', $currency)->first()->currency_rates()->insert($rates);
                Log::debug('Данные по ' . $currency . ' успешно добавлены.');
            }
    }

    protected function deleteOldDates(array $dates) : bool
    {
        return CurrencyRate::whereIn('rate_date', $dates)->delete();
    }
}
