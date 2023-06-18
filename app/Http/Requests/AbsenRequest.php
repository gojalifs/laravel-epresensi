<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nik' => 'required',
            'tanggal' => 'required',
            'tanggal_selesai' => 'nullable',
            'potong_cuti' => 'nullable',
            'alasan' => 'required',
            'jenis_cuti' => 'required'
        ];
    }
}
