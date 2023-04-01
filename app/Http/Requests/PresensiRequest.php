<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresensiRequest extends FormRequest
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
            'jenis' => 'required',
            'jam' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}