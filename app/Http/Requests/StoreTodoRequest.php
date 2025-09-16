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
            'assignee' => 'required|string|max:255',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'required|integer|min:0',
            'status' => [
                'required',
                Rule::in(['Pending', 'In Progress', 'Completed']),
            ],
            'priority' => [
                'required',
                Rule::in(['Low', 'Medium', 'High']),
            ],
        ];
    }
}
