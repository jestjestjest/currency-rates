<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $table = 'currency_rates';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'base_currency_id',
        'rate_date',
        'rate_value',
    ];

    public function base_currency() : BelongsTo
    {
        return $this->belongsTo(BaseCurrency::class, 'base_currency_id', 'id');
    }
}
