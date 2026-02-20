<?php

namespace App\Http\Requests;

use App\DTO\AiDTO;
use Illuminate\Foundation\Http\FormRequest;

class AIRequest extends FormRequest
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
            'modelName' => 'required|string',
            'content' => 'required|string',
        ];
    }
    public function toDTO() : AiDTO {
        return (new AiDTO(
            modelName: $this->validated('modelName'),
            content: $this->validated('content')
        ));
    }
}
