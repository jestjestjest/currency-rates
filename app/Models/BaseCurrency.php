<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaseCurrency extends Model
{
    use HasFactory;

    protected $table = 'base_currencies';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
    ];

    public function currency_rates() : HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'base_currency_id', 'id');
    }

    public function today_currency_rates() : HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'base_currency_id', 'id')
            ->where('rate_date', Carbon::now()->format('Y-m-d'));
    }

    public function history_currency_rates() : HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'base_currency_id', 'id')
            ->where('rate_date','<>', Carbon::now()->format('Y-m-d'))->orderByDesc('rate_date');
    }

}
