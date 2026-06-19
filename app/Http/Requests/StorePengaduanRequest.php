<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengaduanRequest extends FormRequest
{
    /**
     * Hanya mahasiswa yang sudah login yang boleh mengajukan pengaduan.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    /**
     * Aturan validasi server-side untuk form pengaduan baru.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_id'      => ['required', 'exists:kategori_pengaduan,id'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'subjek'           => ['required', 'string', 'min:10', 'max:255'],
            'isi_pengaduan'    => ['required', 'string', 'min:30', 'max:5000'],
        ];
    }

    /**
     * Pesan validasi kustom dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori_id.required'      => 'Kategori pengaduan wajib dipilih.',
            'kategori_id.exists'        => 'Kategori yang dipilih tidak valid.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.date'     => 'Format tanggal kejadian tidak valid.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
            'subjek.required'           => 'Subjek pengaduan wajib diisi.',
            'subjek.min'                => 'Subjek pengaduan minimal 10 karakter.',
            'subjek.max'                => 'Subjek pengaduan maksimal 255 karakter.',
            'isi_pengaduan.required'    => 'Isi pengaduan wajib diisi.',
            'isi_pengaduan.min'         => 'Isi pengaduan minimal 30 karakter agar dapat diproses dengan baik.',
            'isi_pengaduan.max'         => 'Isi pengaduan terlalu panjang (maksimal 5000 karakter).',
        ];
    }
}
