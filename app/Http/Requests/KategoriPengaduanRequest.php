<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriPengaduanRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengelola kategori pengaduan.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Saat update, kecualikan record kategori itu sendiri dari pengecekan unique.
        $kategoriId = $this->route('kategori')?->id;

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_pengaduan', 'nama_kategori')->ignore($kategoriId),
            ],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique'   => 'Kategori dengan nama ini sudah ada.',
            'deskripsi.max'          => 'Deskripsi maksimal 500 karakter.',
        ];
    }
}
