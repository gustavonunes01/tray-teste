<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SalesRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'name' => 'string',
            'price' => 'required|numeric',
            'seller_id' => 'required|integer',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422));
    }
}
