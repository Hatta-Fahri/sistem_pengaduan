<?php

namespace App\Http\Requests;

use App\Models\Pengaduan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah status pengaduan.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Aturan validasi untuk update status pengaduan.
     * catatan_admin wajib diisi jika status = ditolak atau membutuhkan_informasi_tambahan.
     * STATUS_SELESAI sengaja tidak ada di daftar — admin hanya bisa membawa pengaduan ke
     * menunggu_konfirmasi_mahasiswa, baru mahasiswa (atau auto-close) yang menjadikannya selesai.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $statusYangMembutuhkanCatatan = [
            Pengaduan::STATUS_DITOLAK,
            Pengaduan::STATUS_BUTUH_INFO,
        ];

        return [
            'status' => [
                'required',
                Rule::in([
                    Pengaduan::STATUS_MENUNGGU,
                    Pengaduan::STATUS_DIPROSES,
                    Pengaduan::STATUS_BUTUH_INFO,
                    Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
                    Pengaduan::STATUS_DITOLAK,
                ]),
            ],
            'catatan_admin' => [
                // Wajib jika status = ditolak atau membutuhkan_informasi_tambahan
                in_array($this->input('status'), $statusYangMembutuhkanCatatan)
                    ? 'required'
                    : 'nullable',
                'string',
                'max:2000',
            ],
            'bukti_admin' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * Blokir perubahan apapun jika pengaduan sudah berstatus final
     * (selesai_ditangani / ditolak) — status final terkunci permanen.
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            $pengaduan = $this->route('pengaduan');

            if ($pengaduan && $pengaduan->isFinal()) {
                $validator->errors()->add(
                    'status',
                    'Pengaduan ini sudah final (' . $pengaduan->status_label . ') dan tidak dapat diubah lagi.'
                );
            }
        });
    }

    /**
     * Pesan validasi kustom dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required'       => 'Status pengaduan wajib dipilih.',
            'status.in'             => 'Status yang dipilih tidak valid.',
            'catatan_admin.required'=> 'Catatan admin wajib diisi untuk status "Ditolak" atau "Membutuhkan Informasi Tambahan".',
            'catatan_admin.max'     => 'Catatan admin maksimal 2000 karakter.',
            'bukti_admin.mimes'     => 'Bukti harus berupa gambar (JPG, PNG) atau dokumen PDF.',
            'bukti_admin.max'       => 'Ukuran bukti maksimal 5MB.',
        ];
    }
}
