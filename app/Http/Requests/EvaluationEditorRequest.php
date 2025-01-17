<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationEditorRequest extends FormRequest
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
            'article_status_id' => 'required|exists:article_statuses,id',
            'comment'           => 'required|max:10000',
        ];
    }
    public function attributes(): array
    {
        return [
            'article_status_id' => 'resultado',
            'comment'           => 'comentarios',
        ];
    }
}
