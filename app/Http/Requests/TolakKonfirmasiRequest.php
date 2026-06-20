<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TolakKonfirmasiRequest extends FormRequest
{
    /**
     * Hanya mahasiswa pemilik pengaduan yang boleh menolak konfirmasi.
     * Pengecekan kepemilikan & status dilakukan di controller (konsisten dengan show()).
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
            'alasan' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'alasan.required' => 'Mohon jelaskan alasan pengaduan ini belum selesai.',
            'alasan.max'      => 'Alasan maksimal 2000 karakter.',
        ];
    }
}
