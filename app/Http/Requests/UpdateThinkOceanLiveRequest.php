<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateThinkOceanLiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-think-ocean') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'live_enabled' => ['sometimes', 'boolean'],
            'live_title' => ['required', 'string', 'max:120'],
            'live_description' => ['nullable', 'string', 'max:1000'],
            'live_stream_url' => ['nullable', 'url:https', 'max:500', 'required_if_accepted:live_enabled'],
            'live_schedule' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'live_stream_url.required_if_accepted' => 'Add a stream link before switching Live TV on.',
            'live_stream_url.url' => 'The stream link must be a full https:// address.',
        ];
    }
}
