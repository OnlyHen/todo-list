<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Set ke true agar semua orang bisa membuat request ini
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'assignee' => 'sometimes|string|max:255',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high']),
            ],
            'time_tracked' => 'sometimes|integer|min:0',
            'status' => [
               'sometimes',
               Rule::in(['pending', 'open', 'in_progress', 'completed']),
           ],
        ];
    }
}
