<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseCurrencyResource;
use App\Models\BaseCurrency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaseCurrencyController extends BaseApiController
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currencies = BaseCurrency::all();

        return $this->sendResponse(BaseCurrencyResource::collection($currencies), 'Currency retrieved successfully');
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:base_currencies,name',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $currency = BaseCurrency::create($input);

        return $this->sendResponse(new BaseCurrencyResource($currency), 'Currency created successfully.');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $currency = BaseCurrency::whereId($id);

        if ($currency->get()->isEmpty()) {
            return $this->sendError('Currency not found.');
        }

        $currency = $currency->with(['today_currency_rates', 'history_currency_rates'])->first();

        return $this->sendResponse(new BaseCurrencyResource($currency), 'Currency retrieved successfully.');
    }

    /**
     * @param Request $request
     * @param BaseCurrency $currency
     * @return JsonResponse
     */
    public function update(Request $request, BaseCurrency $currency): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:base_currencies,name',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $currency->name = $input['name'];
        $currency->save();

        return $this->sendResponse(new BaseCurrencyResource($currency), 'Currency updated successfully.');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $currency = BaseCurrency::find($id);
        $currency->delete();

        return $this->sendResponse([], 'Currency deleted successfully.');
    }
}
