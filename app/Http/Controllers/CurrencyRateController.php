<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyRateResource;
use App\Models\CurrencyRate;
use Illuminate\Http\JsonResponse;

class CurrencyRateController extends BaseApiController
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $rates = CurrencyRate::all();

        return $this->sendResponse(CurrencyRateResource::collection($rates), 'Rates retrieved successfully');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $rates = CurrencyRate::find($id);

        if (is_null($rates)) {
            return $this->sendError('Rates not found.');
        }

        return $this->sendResponse(new CurrencyRateResource($rates), 'Rates retrieved successfully.');
    }
}
