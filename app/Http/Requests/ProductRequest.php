<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|min:5',
            'image_path' => 'required|image:jpg,jpeg,png,',
            'price' => 'required|numeric|min:0|not_in:0',
            'quanity' => 'required|numeric|min:0',
            'sale' => 'numeric|min:0|max:100',
            'status' => 'required',
            'category' => 'required',
            'supplier' => 'required',
            'description' => 'required',
            'contentProduct' => 'required'
        ];
    }
}
