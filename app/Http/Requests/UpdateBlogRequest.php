<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:255', 'min:3'],
            'content' => ['string', 'max:255', 'min:10'],
            'image' => ['image'],
            'status' => [ Rule::in([1,0])],
            'publish_date' => ['date']
        ];
    }
}
