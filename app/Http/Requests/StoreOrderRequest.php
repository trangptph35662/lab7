<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'customer' => 'array|required_array_keys: phone, email, name, address',
            'customer.phone' => 'required|numeric|digits_between:10,11|unique:customers,phone',
            'customer.email' => 'required|unique:customers,email',
            'customer.name' => 'required',
            'customer.address' => 'required',

            'supplier' => 'array|required_array_keys: phone, email, name, address',
            'supplier.phone' => 'required|numeric|digits_between:10,11|unique:suppliers,phone',
            'supplier.email' => 'required|email|unique:suppliers,email',
            'supplier.name' => 'required',
            'supplier.address' => 'required',

            'products' => 'array',
            'products.*' => 'array|required_array_keys: name, decription, price, stoke_quantity',
            'products.*.name' => 'required|unique:products,name',
            'products.*.decription' => 'nullable',
            'products.*.price' => 'required|integer|min:0',
            'products.*.stoke_quantity' => 'required|integer|min:0',
            'products.*.image' => 'nullable|image|max:2048',

            'order_details' => 'array',
            'order_details.*' => 'array|required_array_keys: qty',
            'order_details.*.qty' => 'required|integer|min:0|lte:products.*.stoke_quantity',
        ];
    }

    public function attributes()
    {
        return [
            'customer.phone' => ' customer phone number',
            'customer.email' => ' customer email',
            'customer.name' => ' customer name',
            'customer.address' => ' customer address',

            'supplier.phone' => ' supplier phone number',
            'supplier.email' => ' supplier email',
            'supplier.name' => ' supplier name',
            'supplier.address' => ' supplier address',

            'products.*.name' => 'product name',
            'products.*.decription' => 'product decription',
            'products.*.price' => 'product price',
            'products.*.stoke_quantity' => 'product stoke quantity',
            'products.*.image' => 'product image',

            'order_details.*.qty' => ' quantity buy',

        ];
    }
}
