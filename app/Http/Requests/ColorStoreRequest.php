<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorStoreRequest extends FormRequest
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
            'name' => 'required|unique:colors,name,'.$this->color_id.',id'.'|min:2|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'This color already exists',
        ];
    }
}
