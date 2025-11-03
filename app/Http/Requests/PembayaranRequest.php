<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ✅ Ubah ke true
    }

    public function rules(): array
    {
        return [
            'santri_id' => 'required|exists:santris,id',
            'jenis_pembayaran' => 'required|in:spp,pendaftaran,seragam,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'bulan_bayar' => 'nullable|string',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'status' => 'required|in:pending,lunas,dibatalkan',
            'bukti_transfer' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ];
    }
}