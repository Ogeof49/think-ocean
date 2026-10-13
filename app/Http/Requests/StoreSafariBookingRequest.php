<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSafariBookingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'package_name' => ['required', Rule::in(['Murchison Express', 'Gorillas & Savannah', 'Pearl of Africa'])],
            'travel_date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'travellers' => ['required', 'integer', 'min:1', 'max:20'],
            'amount' => ['required', 'numeric', 'min:1000', 'max:100000000'],
            'phone_number' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'payment_method' => ['required', Rule::in(['mtn_momo', 'airtel_money', 'pesapal'])],
        ];
    }

    public function messages(): array
    {
        return [
            'travel_date.after_or_equal' => 'Please select a travel date from tomorrow onwards.',
            'phone_number.regex' => 'Enter a valid mobile money phone number.',
            'amount.min' => 'Enter an amount of at least UGX 1,000.',
        ];
    }
}
