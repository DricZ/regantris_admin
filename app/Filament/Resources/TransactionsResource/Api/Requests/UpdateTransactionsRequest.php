<?php

namespace App\Filament\Resources\TransactionsResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionsRequest extends FormRequest
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
			'member_id' => 'required|integer',
			'hotel_id' => 'required|integer',
			'type' => 'required',
			'nominal' => 'required|numeric',
			'deleted_at' => 'required'
		];
    }
}
