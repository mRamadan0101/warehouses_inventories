<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CustomApiRequest;

class StocktransferRequest extends CustomApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
