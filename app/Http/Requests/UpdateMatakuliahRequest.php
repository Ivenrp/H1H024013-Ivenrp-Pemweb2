<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMatakuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('matakuliah')->id;

        return [
            'kode'     => ['sometimes', 'string', 'max:10', Rule::unique('matakuliahs', 'kode')->ignore($id)],
            'nama'     => ['sometimes', 'string', 'max:100'],
            'sks'      => ['sometimes', 'integer', 'min:1', 'max:6'],
            'semester' => ['sometimes', 'integer', 'min:1', 'max:14'],
        ];
    }
}
