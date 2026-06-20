<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BalasInformasiRequest extends FormRequest
{
    /**
     * Pengecekan kepemilikan & status dilakukan di controller (konsisten dengan aksi lain).
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'balasan' => ['required', 'string', 'max:2000'],
            'bukti'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'balasan.required' => 'Mohon isi balasan informasi yang diminta admin.',
            'balasan.max'       => 'Balasan maksimal 2000 karakter.',
            'bukti.mimes'       => 'Bukti harus berupa gambar (JPG, PNG) atau dokumen PDF.',
            'bukti.max'         => 'Ukuran bukti maksimal 5MB.',
        ];
    }
}
