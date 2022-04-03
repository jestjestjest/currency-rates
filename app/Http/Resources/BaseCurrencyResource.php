<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseCurrencyResource extends JsonResource
{
    /**
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'today_rates' => CurrencyRateResource::collection($this->whenLoaded('today_currency_rates')),
            'history_rates' => CurrencyRateResource::collection($this->whenLoaded('history_currency_rates')),
//            'history_rates' => CurrencyRateResource::collection($this->whenLoaded('history_currency_rates'))
//                ->collection->groupBy('rate_date'),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
