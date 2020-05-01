<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
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
            'name_my' => 'required',
            'name_en' => 'required',
            'parent_id'=> 'required',
            'description'=> 'required',
            'prices'=> 'required|numeric|min:2|max:999999'
        ];
    }

    public function messages()
    {
        return [
            'name_my.required' => 'Name (Burmese) is required.',
            'name_en.required' => 'Name (English) is required.',
            'parent_id.required' => 'Category is required.',
            'description.required' => 'Description is required',
            'prices.required' => 'Product Prices is required'
        ];
    }
}
