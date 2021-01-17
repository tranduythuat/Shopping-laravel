<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeStoreRequest extends FormRequest
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
        return [
            'name' => 'required|unique:sizes,name,'.$this->size_id.',id'.'|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => "The name's size already exists",
        ];
    }
}
