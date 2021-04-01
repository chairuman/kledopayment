<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // initiate rules
        $rules = [];
        
        // condition to check the request method
        switch ($this->method()) {
            // if the request method id POST rules will be payment_name that required
            // and if the request method is DELETE rules will be payment_id that required
            case 'POST':
                $rules['payment_name'] = 'required';
                break;
            case 'DELETE':
                $rules['payment_id'] = 'required';
                break;
        }

        // return array of rules
        return $rules;
    }


    /**
     * function when request validation  is error
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
