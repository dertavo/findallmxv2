<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfoUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            return [
                'nombre' => 'nullable|string|max:100',
                'ap' => 'nullable|string|max:100',
                'am' => 'nullable|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:100',
                'estado' => 'nullable|string|max:100',
                'cp' => 'nullable|string|max:10',
                'telefono' => 'nullable|string|max:20',
                'public_info' => 'nullable|string',
            ];
        ];
    }

    public function messages(): array
    {
        return [
           
        ];
    }


   
}
