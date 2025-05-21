<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaginateRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            "per_page" => "required|integer",
            "page" => "required|integer",
            "order_by" => "string",
            "order" => "integer",
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
